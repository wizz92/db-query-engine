<?php
namespace App\Service\DatabaseQueryEngine\Models\ReadOnlyTests\Mongodb;

use App\Base\Eloquent\BaseMongodbModel as Model;

/**
 * Class TestDQE
 * @package App\Service\DatabaseQueryEngine\Models
 */
class TestDQE extends Model
{
    protected $collection = 'test_dqe';
    protected $connection = 'dqe_mongodb';
}