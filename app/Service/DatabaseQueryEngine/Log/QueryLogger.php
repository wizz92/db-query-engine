<?php
namespace App\Service\DatabaseQueryEngine\Log;

use App\Service\DatabaseQueryEngine\DTOs\CustomQueryResultsDTO;
use App\Service\DatabaseQueryEngine\Invoker\ClickhouseInvoker;
use App\Service\DatabaseQueryEngine\Invoker\Contracts\ObservableInterface;
use App\Service\DatabaseQueryEngine\Invoker\Invoker;
use App\Service\DatabaseQueryEngine\Log\Contracts\QueryLoggerInterface;
use App\Service\DatabaseQueryEngine\Models\CustomQueryLog;
use App\Service\DatabaseQueryEngine\Repositories\Contracts\CustomQueryLogRepositoryInterface;
use Illuminate\Database\DatabaseManager;

/**
 * Class QueryLoggerInterface
 * @package App\Service\DatabaseQueryEngine\Log\Contracts
 */
class QueryLogger implements QueryLoggerInterface
{
    /**
     * @var CustomQueryLogRepositoryInterface $customQueryLogRepository
     */
    private $customQueryLogRepository;

    /**
     * QueryLogger constructor.
     * @param CustomQueryLogRepositoryInterface $customQueryLogRepository
     */
    public function __construct(CustomQueryLogRepositoryInterface $customQueryLogRepository)
    {
        $this->customQueryLogRepository = $customQueryLogRepository;
    }

    /**
     * @param ObservableInterface $invoker
     * @return mixed
     */
    public function update(ObservableInterface $invoker)
    {
        /**
         * @var CustomQueryResultsDTO $queryResults
         */
        $queryResults = $invoker->getQueryResults();

        if ($queryResults instanceof CustomQueryResultsDTO == false ||
            $invoker->getConcreteInvoker() instanceof ClickhouseInvoker == true) {
            return;
        }

        $logs = $queryResults->getQueryLog()[0];

        $customQuery = $queryResults->getCustomQuery();
        $this->customQueryLogRepository->create([
            'custom_query_id' => $customQuery->getId(),
            'request_log_id' => $queryResults->getRequestLogId(),
            'execution_time' => $logs['time'],
            'query' => $logs['query'],
//            'results' => json_encode($queryResults->getResults()), // commented for saving place on server
            'status' => CustomQueryLog::STATUS_SUCCESS,
        ]);

        if ($logs['time'] >= Invoker::TIME_EXECUTION_LIMIT) {
            $customQuery->addTimeoutIssue();
        };
    }
}
