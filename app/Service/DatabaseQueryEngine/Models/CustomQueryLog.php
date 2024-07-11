<?php

namespace App\Service\DatabaseQueryEngine\Models;

use App\Service\DatabaseQueryEngine\Models\Contracts\CustomQueryLogInterface;
use App\Base\Eloquent\BaseModel as Model;

/**
 * Class CustomQueries
 * @package App\Service\DatabaseQueryEngine\Models
 */
class CustomQueryLog extends Model implements CustomQueryLogInterface
{
    const STATUS_SUCCESS = 1;
    const STATUS_FAILED = 2;
    const STATUS_EXECUTION_BLOCKED = 3;
    protected $table = 'custom_query_logs2'; // we changed name from custom_query_logs after accident 09.06.2024

    protected $connection = 'mysql';
    protected $fillable = [
        'custom_query_id',
        'request_log_id',
        'execution_time',
        'query',
        'type',
        'results',
        'status'
    ];

    public function customQuery()
    {
        return $this->belongsTo(CustomQuery::class, 'custom_query_id');
    }

    /**
     * @return bool
     */
    public function isSuccessExecuted(): bool
    {
        return $this->status === static::STATUS_SUCCESS;
    }

    /**
     * @return bool
     */
    public function isExecutionBlocked(): bool
    {
        return $this->status === static::STATUS_EXECUTION_BLOCKED;
    }

    /**
     * @return bool
     */
    public function isExecutionFailed(): bool
    {
        return $this->status === static::STATUS_FAILED;
    }
}
