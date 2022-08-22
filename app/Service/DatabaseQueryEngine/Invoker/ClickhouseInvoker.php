<?php

namespace App\Service\DatabaseQueryEngine\Invoker;

use App\Service\DatabaseQueryEngine\Invoker\Contracts\ConcreteInvokerInterface;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\QueryException;

/**
 * Class ClickhouseInvoker
 * @package App\Service\DatabaseQueryEngine\Invoker
 */
class ClickhouseInvoker implements ConcreteInvokerInterface
{
    const CONNECTION = 'clickhouse';

    /**
     * @return string
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
            try {
                $db = $databaseManager->connection(self::CONNECTION)->getClient();
                return $db->select($query)->rows();
            } catch (QueryException $exception) {
                throw new QueryExecuteException($exception->getMessage());
            }
        };
    }
}
