<?php

namespace App\Service\DatabaseQueryEngine\Invoker;

use App\Service\DatabaseQueryEngine\Invoker\Contracts\ConcreteInvokerInterface;
use App\Service\DatabaseQueryEngine\Invoker\Exceptions\QueryExecuteException;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\QueryException;

/**
 * Class MySQLInvoker
 *
 * @package App\Service\DatabaseQueryEngine
 */
class MySQLInvoker implements ConcreteInvokerInterface
{
    const CONNECTION = 'dqe_mysql::read';

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
                return $databaseManager->select($query);
            } catch (QueryException $exception) {
                throw new QueryExecuteException($exception->getMessage());
            }
        };
    }
}
