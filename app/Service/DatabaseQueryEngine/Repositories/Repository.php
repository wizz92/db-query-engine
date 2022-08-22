<?php

namespace App\Service\DatabaseQueryEngine\Repositories;

use App\Service\DatabaseQueryEngine\Models\CustomQuery;
use App\Service\DatabaseQueryEngine\Models\CustomQueryLog;
use App\Service\DatabaseQueryEngine\Repositories\Contracts\CustomQueryRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CustomQueryRepository
 * @package App\Service\DatabaseQueryEngine\Repositories
 */
abstract class Repository
{
    /**
     * @var string
     */
    protected $modelClass;

    /**
     * @return string
     */
    protected function getModelClass() : string
    {
        return $this->modelClass;
    }
}