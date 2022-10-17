<?php

namespace App\Service\DatabaseQueryEngine\Models;

use App\Service\DatabaseQueryEngine\Models\Contracts\CustomQueryInterface;
use App\Base\Eloquent\BaseModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CustomQueries
 *
 * @package App\Service\DatabaseQueryEngine\Models
 */
class CustomQuery extends Model implements CustomQueryInterface
{
    const STATUS_PENDING_APPROVE = 1;
    const STATUS_APPROVED = 2;
    const STATUS_TIMEOUT_REJECTED= 3;
    const STATUS_FAILED_QUERY_REJECTED = 5;
    const STATUS_BLOCKED = 4;


    const MAX_TIMEOUTS = 5000;
    const MAX_TIMEOUTS_EXCEPTION = 15000;

    const TYPE_MYSQL = 1;
    const TYPE_MONGODB = 2;

    const TYPE_DESCRIPTIONS = [
        self::TYPE_MYSQL => 'TYPE_MYSQL',
        self::TYPE_MONGODB => 'TYPE_MONGODB',
    ];

    public const MAX_TIMEOUT_QUERY_EXCEPTIONS_IDS = [
        16,
        76,
        155, // app_stat004
        156, // cem_cont011
        201, // returned_ams006
    ];

    const STATUS_DESCRIPTIONS = [
        self::STATUS_PENDING_APPROVE => 'STATUS_PENDING_APPROVE',
        self::STATUS_APPROVED => 'STATUS_APPROVED',
        self::STATUS_TIMEOUT_REJECTED => 'STATUS_TIMEOUT_REJECTED',
        self::STATUS_FAILED_QUERY_REJECTED => 'STATUS_FAILED_QUERY_REJECTED',
        self::STATUS_BLOCKED => 'STATUS_BLOCKED',
    ];

    protected $table = 'custom_queries';
    protected $connection = 'mysql';
    protected $fillable = [
        'name',
        'query',
        'type',
        'description',
        'created_by',
        'status'
    ];

    protected $appends = [
        'type_description',
        'status_description',
    ];

    /**
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->status === static::STATUS_APPROVED;
    }

    /**
     * @return mixed|null
     */
    public function getTypeDescriptionAttribute()
    {
        return static::TYPE_DESCRIPTIONS[$this->type] ?? null;
    }

    /**
     * @return mixed|null
     */
    public function getStatusDescriptionAttribute()
    {
        return static::STATUS_DESCRIPTIONS[$this->status] ?? null;
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->status === static::STATUS_BLOCKED;
    }

    /**
     * @return bool
     */
    public function isPendingApprove(): bool
    {
        return $this->status === static::STATUS_PENDING_APPROVE;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @return mixed
     */
    public function getCreatorId()
    {
        return $this->created_by;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customQueryLogs()
    {
        return $this->hasMany(CustomQueryLog::class, 'custom_query_id');
    }

    public function addTimeoutIssue()
    {
        $this->query_timeout_count++;
        $this->save();
    }

    public function flushTimeouts()
    {
        $this->query_timeout_count = 0;
        $this->save();
    }

    /**
     * @return bool
     */
    public function hasTimeoutsLimit(): bool
    {
//        $maxTimeouts = in_array($this->id, static::MAX_TIMEOUT_QUERY_EXCEPTIONS_IDS)
//            ? static::MAX_TIMEOUTS_EXCEPTION
//            : static::MAX_TIMEOUTS;

        return $this->query_timeout_count >= static::MAX_TIMEOUTS_EXCEPTION;
    }
}
