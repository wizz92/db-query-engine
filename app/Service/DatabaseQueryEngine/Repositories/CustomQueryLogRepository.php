<?php

namespace App\Service\DatabaseQueryEngine\Repositories;

use App\Service\DatabaseQueryEngine\Models\Contracts\CustomQueryLogInterface;
use App\Service\DatabaseQueryEngine\Models\CustomQueryLog;
use App\Service\DatabaseQueryEngine\Repositories\Contracts\CustomQueryLogRepositoryInterface;

/**
 * Class CustomQueryLogRepository
 * @package App\Service\DatabaseQueryEngine\Repositories
 */
class CustomQueryLogRepository extends Repository implements CustomQueryLogRepositoryInterface
{
    /**
     * @var string
     */
    protected $modelClass = CustomQueryLog::class;

    /**
     * @param $data
     * @return mixed
     */
    public function create($data) : CustomQueryLogInterface
    {
        return ($this->getModelClass())::create($data);
    }

    /**
     * @param $queryId
     * @param int $limit
     * @return mixed
     */
    public function findForQuery($queryId, $limit = null)
    {
        $query =  ($this->getModelClass())::where('custom_query_id', $queryId)
            ->orderBy('id', 'desc');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * @param int $queryId
     * @return int
     */
    public function getFailedAttemptsCount(int $queryId): int
    {
        return ($this->getModelClass())::where('custom_query_id', $queryId)
            ->where('status', CustomQueryLog::STATUS_FAILED)
            ->count();
    }
}