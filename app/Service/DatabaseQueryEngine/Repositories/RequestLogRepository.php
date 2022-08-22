<?php

namespace App\Service\DatabaseQueryEngine\Repositories;

use App\Service\DatabaseQueryEngine\Models\Contracts\CustomQueryLogInterface;
use App\Service\DatabaseQueryEngine\Models\CustomQueryLog;
use App\Service\DatabaseQueryEngine\Models\RequestLog;
use App\Service\DatabaseQueryEngine\Repositories\Contracts\CustomQueryLogRepositoryInterface;
use App\Service\DatabaseQueryEngine\Repositories\Contracts\RequestLogRepositoryInterface;

/**
 * Class CustomQueryLogRepository
 * @package App\Service\DatabaseQueryEngine\Repositories
 */
class RequestLogRepository extends Repository implements RequestLogRepositoryInterface
{
    /**
     * @var string
     */
    protected $modelClass = RequestLog::class;

    /**
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        return ($this->getModelClass())::create($data);
    }
}