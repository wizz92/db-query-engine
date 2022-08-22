<?php
namespace App\Service\DatabaseQueryEngine\Models\ReadOnlyTests\Mysql;

use App\Base\Eloquent\BaseModel as Model;

/**
 * Class TestDQE
 * @package App\Service\DatabaseQueryEngine\Models
 */
class TestDQE extends Model
{
    protected $table = 'test_dqe';
    protected $connection = 'dqe_mysql';
}