<?php

namespace App\Service\DatabaseQueryEngine\Security\Rules\Common;

use App\Service\DatabaseQueryEngine\DTOs\CustomQueryDTO;
use App\Service\DatabaseQueryEngine\Models\CustomQuery;
use App\Service\DatabaseQueryEngine\Repositories\Contracts\CustomQueryRepositoryInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\AllowQueryExecuteRuleInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\MongoDbRuleInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\MysqlRuleInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\RuleInterface;
use App\Service\DatabaseQueryEngine\Security\Rules\Rule;

/**
 * Class ApprovedQuery
 * @package App\Service\DatabaseQueryEngine\Security\Rules\Sql
 */
class QueryTimeout extends Rule implements
    RuleInterface,
    MysqlRuleInterface,
    MongoDbRuleInterface,
    AllowQueryExecuteRuleInterface
{
    /**
     * @var CustomQueryRepositoryInterface $customQueryRepository
     */
    private $customQueryRepository;
    /**
     * ApprovedQuery constructor.
     * @param CustomQueryRepositoryInterface $customQueryRepository
     */
    public function __construct(CustomQueryRepositoryInterface $customQueryRepository)
    {
        $this->customQueryRepository = $customQueryRepository;
    }

    /**
     * @param mixed | CustomQueryDTO $data
     * @return bool
     * @throws
     */
    public function isAllowed($data = null): bool
    {
        $customQuery = $this->customQueryRepository->findByName($data->getName());

        if (!$customQuery->hasTimeoutsLimit()) {
            return true;
        }

        $customQuery->flushTimeouts();
        $this->customQueryRepository->updateStatus($customQuery, CustomQuery::STATUS_TIMEOUT_REJECTED);
        $this->setErrorMessage("Query is not allowed. Timeout reject");
        return false;
    }
}