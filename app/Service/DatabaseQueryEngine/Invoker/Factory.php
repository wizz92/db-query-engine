<?php


namespace App\Service\DatabaseQueryEngine\Invoker;

use App\Service\DatabaseQueryEngine\Invoker\Contracts\BaseInvokerInterface;
use App\Service\DatabaseQueryEngine\Invoker\Contracts\FactoryInterface;
use App\Service\DatabaseQueryEngine\Invoker\Contracts\InvokerInterface;
use App\Service\DatabaseQueryEngine\Log\Contracts\QueryLoggerInterface;
use Illuminate\Database\DatabaseManager;

/**
 * Class MySQLInvoker
 * @package App\Service\DatabaseQueryEngine
 */
class Factory implements FactoryInterface
{
    const TYPE_MYSQL = 1;
    const TYPE_MONGODB = 2;
    const TYPE_CLICKHOUSE = 3;

    private $definitions = [
        self::TYPE_MYSQL => MySQLInvoker::class,
        self::TYPE_MONGODB => MongoDbInvoker::class,
        self::TYPE_CLICKHOUSE => ClickhouseInvoker::class,
    ];

    /**
     * @var BaseInvokerInterface $invoker
     */
    private $baseInvoker;

    /**
     * AbstractDriverInvoker constructor.
     * @param BaseInvokerInterface $invoker
     * @param QueryLoggerInterface $logger
     */
    public function __construct(BaseInvokerInterface $invoker, QueryLoggerInterface $logger)
    {
        $invoker->attach($logger);
        $this->baseInvoker = $invoker;
    }

    /**
     * @param $invokerType
     * @return InvokerInterface
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function create($invokerType): InvokerInterface
    {
        $concreteInvoker = app()->make($this->definitions[$invokerType]);
        $this->baseInvoker->setConcreteInvoker($concreteInvoker);
        return $this->baseInvoker;
    }
}
