<?php

namespace App\Service\DatabaseQueryEngine\Models;

use App\Base\Eloquent\BaseModel as Model;

/**
 * Class RequestLog
 * @package App\Service\DatabaseQueryEngine\Models
 */
class RequestLog extends Model
{
    protected $table = 'dqe_request_logs';

    protected $connection = 'mysql';
    protected $fillable = [
        'input',
        'headers',
        'route',
        'ip',
        'user_id',
    ];
}
