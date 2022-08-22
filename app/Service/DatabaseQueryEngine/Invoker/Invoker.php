<?php


namespace App\Service\DatabaseQueryEngine\Invoker;

use App\Models\Users\User;
use App\Service\DatabaseQueryEngine\DTOs\CustomQueryResultsDTO;
use App\Service\DatabaseQueryEngine\Invoker\Contracts\BaseInvokerInterface;
use App\Service\DatabaseQueryEngine\Invoker\Contracts\ConcreteInvokerInterface;
use App\Service\DatabaseQueryEngine\Invoker\Contracts\InvokerInterface;
use App\Service\DatabaseQueryEngine\Invoker\Contracts\SetableProviderInterface;
use App\Service\DatabaseQueryEngine\Invoker\Contracts\SettableRequestLogIdInterface;
use App\Service\DatabaseQueryEngine\Invoker\Contracts\SetupableConnectionInterface;
use App\Service\DatabaseQueryEngine\Log\Contracts\ObserverInterface;
use App\Service\DatabaseQueryEngine\Models\Contracts\CustomQueryInterface;
use Illuminate\Database\DatabaseManager;

/**
 * Class MySQLInvoker
 * @package App\Service\DatabaseQueryEngine
 */
class Invoker implements BaseInvokerInterface
{
    const TIME_EXECUTION_LIMIT = 100 * 1000;

    /**
     * @var DatabaseManager $dbManager
     */
    private $dbManager;

    /**
     * @var ConcreteInvokerInterface $concreteInvoker
     */
    private $concreteInvoker;

    /**
     * @var ObserverInterface[] $loggers
     */
    private $loggers = [];

    private $query;
    private $results;
    private $requestLogId;

    /**
     * MySQLInvoker constructor.
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->dbManager = $databaseManager;
    }

    /**
     * @param CustomQueryInterface $customQuery
     * @param null $params
     * @return array|mixed
     */
    public function execute(CustomQueryInterface $customQuery, $params = null)
    {
        $this->query = $customQuery;
        $this->dbManager->setDefaultConnection($this->concreteInvoker->getConnection());
        $this->dbManager->enableQueryLog();

        $query = $customQuery->getQuery();

        if ($params) {
            $params = json_decode($params, true);
            foreach ($params as $placeholder => $value) {
                $query = str_replace("{{$placeholder}}", $value, $query);
            }
        };

        $provider = $this->concreteInvoker->getProvider();
        $this->results = $provider($this->dbManager, $query);
        $this->emitQueryExecuted();
        return $this->results;
    }


    /**
     * @param ObserverInterface $logger
     * @return mixed
     */
    public function attach(ObserverInterface $logger)
    {
        $this->loggers[] = $logger;
    }

    /**
     * @param ObserverInterface $logger
     * @return mixed
     */
    public function detach(ObserverInterface $logger)
    {
        foreach ($this->loggers as $key => $observer) {
            if ($observer === $logger) {
                unset($this->loggers[$key]);
                return;
            }
        }
    }

    /**
     * @return CustomQueryResultsDTO
     */
    public function getQueryResults()
    {
        return new CustomQueryResultsDTO([
            'customQuery' => $this->query,
            'results' => $this->results,
            'requestLogId' => $this->requestLogId,
            'queryLog' => $this->dbManager->getQueryLog(),
        ]);
    }


    /**
     * Notify observers about query execution
     */
    private function emitQueryExecuted()
    {
        foreach ($this->loggers as $logger) {
            $logger->update($this);
        }
    }

    /**
     * @param ConcreteInvokerInterface $concreteInvoker
     * @return mixed
     */
    public function setConcreteInvoker(ConcreteInvokerInterface $concreteInvoker)
    {
        $this->concreteInvoker = $concreteInvoker;
    }

    /**
     * @param $requestLogId
     * @return BaseInvokerInterface
     */
    public function setRequestLogId($requestLogId) : InvokerInterface
    {
        $this->requestLogId = $requestLogId;
        return $this;
    }

    /**
     * @return ConcreteInvokerInterface
     */
    public function getConcreteInvoker(): ConcreteInvokerInterface
    {
        return $this->concreteInvoker;
    }
}
