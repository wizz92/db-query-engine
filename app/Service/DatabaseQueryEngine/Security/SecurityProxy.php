<?php
namespace App\Service\DatabaseQueryEngine\Security;

use App\Service\DatabaseQueryEngine\Contracts\DatabaseQueryEngineInterface;
use App\Service\DatabaseQueryEngine\DTOs\CreateCustomQueryDTO;
use App\Service\DatabaseQueryEngine\DTOs\CustomQueryResultsDTO;
use App\Service\DatabaseQueryEngine\DTOs\ExecuteCustomQueryDTO;
use App\Service\DatabaseQueryEngine\Invoker\Contracts\FactoryInterface;
use App\Service\DatabaseQueryEngine\Invoker\Exceptions\QueryExecuteException;
use App\Service\DatabaseQueryEngine\Models\Contracts\CustomQueryInterface;
use App\Service\DatabaseQueryEngine\Models\CustomQuery;
use App\Service\DatabaseQueryEngine\Models\CustomQueryLog;
use App\Service\DatabaseQueryEngine\Repositories\Contracts\CustomQueryLogRepositoryInterface;
use App\Service\DatabaseQueryEngine\Repositories\Contracts\CustomQueryRepositoryInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\RulesEngineInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\SecurityProxyInterface;
use App\Service\DatabaseQueryEngine\Security\Exceptions\SecurityException;

/**
 * Class SecurityProxy
 * @package App\Service\DatabaseQueryEngine\Security
 */
class SecurityProxy implements SecurityProxyInterface
{
    /**
     * @var CustomQueryRepositoryInterface $customQueryRepository
     */
    private $customQueryRepository;

    /**
     * @var CustomQueryLogRepositoryInterface $customQueryLogRepository
     */
    private $customQueryLogRepository;

    /**
     * @var FactoryInterface $invokerFactory
     */
    private $invokerFactory;

    /**
     * @var RulesEngineInterface $rulesEngine
     */
    private $rulesEngine;

    /**
     * SecurityProxy constructor.
     * @param CustomQueryRepositoryInterface $customQueryRepository
     * @param FactoryInterface $invokerFactory
     * @param RulesEngineInterface $rulesEngine
     */
    public function __construct(
        CustomQueryRepositoryInterface $customQueryRepository,
        FactoryInterface $invokerFactory,
        CustomQueryLogRepositoryInterface $customQueryLogRepository,
        RulesEngineInterface $rulesEngine
    ) {
        $this->invokerFactory = $invokerFactory;
        $this->customQueryRepository = $customQueryRepository;
        $this->customQueryLogRepository = $customQueryLogRepository;
        $this->rulesEngine = $rulesEngine;
    }

    /**
     * @param CreateCustomQueryDTO $data
     * @return CustomQueryInterface
     * @throws SecurityException
     */
    public function storeQuery($data): CustomQueryInterface
    {
        $this->rulesEngine->setData($data)->run($data->getDatabaseType(), 'store');

        $errors = $this->rulesEngine->getErrors();

        if (!empty($errors)) {
            throw new SecurityException("Failed to store query", 0, null, $errors);
        }

        return $this->customQueryRepository->create([
            'type' => $data->getDatabaseType(),
            'name' => $data->getName(),
            'description' => $data->getDescription(),
            'created_by' => $data->getCreatorId(),
            'query' => $data->getQuery()
        ]);
    }

    /**
     * @param ExecuteCustomQueryDTO $dto
     * @return mixed
     * @throws SecurityException
     */
    public function executeQuery($dto)
    {
        $customQuery = $this->customQueryRepository->findByName($dto->getName());

        $this->rulesEngine->setData($customQuery)->run($customQuery->getType(), 'execute');

        $errors = $this->rulesEngine->getErrors();


        if (!empty($errors)) {
            throw new SecurityException("Failed to execute query", 0, null, $errors);
        }

        try {
            return $this
                ->invokerFactory
                ->create($customQuery->getType())
                ->setRequestLogId($dto->getRequestLogId())
                ->execute($customQuery, $dto->getParams());
        } catch (QueryExecuteException $exception) {
            $this->customQueryLogRepository->create([
                'custom_query_id' => $customQuery->getId(),
                'request_log_id' => $dto->getRequestLogId(),
                'query' => $customQuery->getQuery(),
                'results' => $exception->getMessage(),
                'status' => CustomQueryLog::STATUS_FAILED,
            ]);
            $this->customQueryRepository->updateStatus($customQuery, CustomQuery::STATUS_FAILED_QUERY_REJECTED);
            throw new SecurityException("Query error", 0, null, [$exception->getMessage()]);
        }
    }
}