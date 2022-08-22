<?php

namespace App\Service\DatabaseQueryEngine\Invoker;

use App\Service\DatabaseQueryEngine\Invoker\Contracts\ConcreteInvokerInterface;
use App\Service\DatabaseQueryEngine\Invoker\Exceptions\QueryExecuteException;
use App\Service\DatabaseQueryEngine\Services\Transformers\Contracts\TransformerQueryInterface;
use Illuminate\Database\DatabaseManager;
use Symfony\Component\Debug\Exception\FatalErrorException;

/**
 * Class MySQLInvoker
 * @package App\Service\DatabaseQueryEngine
 */
class MongoDbInvoker implements ConcreteInvokerInterface
{
    const CONNECTION = 'dqe_mongodb';

    /**
     * @var \App\Service\DatabaseQueryEngine\Services\Transformers\Contracts\TransformerQueryInterface
     */
    protected $transformer;

    /**
     * MongoDbInvoker constructor.
     * @param \App\Service\DatabaseQueryEngine\Services\Transformers\Contracts\TransformerQueryInterface $transformer
     */
    public function __construct(TransformerQueryInterface $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * @return mixed|string
     */
    public function getConnection()
    {
        return static::CONNECTION;
    }

    /**
     * @return \Closure
     */
    public function getProvider(): \Closure
    {
        return function (DatabaseManager $databaseManager, $query) {
            $query = json_decode($query, true);

            try {
                if (is_null($query)) {
                    throw new QueryExecuteException('Bad query format. Check arguments or query body.');
                }

                $query['body'] = $this->transformer
                    ->setQueryBody($query['body'])
                    ->transform();

                $records = $databaseManager
                    ->collection($query['collection'])
                    ->raw()
                    ->find($query['body'])
                    ->toArray();

                if (!isset($query['fields'])) {
                    throw new QueryExecuteException('Bad query format. You need to set `fields` for selecting');
                }

                $fieldsForResponse = $query['fields'];

                return $this->transformer
                    ->setFieldsForResponse($fieldsForResponse)
                    ->getResponse($records);
            } catch (FatalErrorException $exception) {
                throw new QueryExecuteException($exception->getMessage());
            }
        };
    }
}
