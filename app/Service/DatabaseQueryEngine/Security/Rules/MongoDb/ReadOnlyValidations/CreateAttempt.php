<?php

namespace App\Service\DatabaseQueryEngine\Security\Rules\MongoDb\ReadOnlyValidations;

use App\Service\DatabaseQueryEngine\Models\ReadOnlyTests\Mongodb\TestDQE;
use App\Service\DatabaseQueryEngine\Security\Rules\Contracts\DbQueryAttemptInterface;
use App\Service\DatabaseQueryEngine\Security\Rules\Common\ReadOnlyValidations\BaseCreateAttempt;
use MongoDB\Driver\Exception\BulkWriteException;
use MongoDB\Driver\Exception\RuntimeException;

/**
 * Class CreateAttempt
 * @package App\Service\DatabaseQueryEngine\Security\Rules\MongoDb\ReadOnlyValidations
 */
class CreateAttempt extends BaseCreateAttempt implements DbQueryAttemptInterface
{
    /**
     * @return string
     */
    protected function getModelClass()
    {
        return TestDQE::class;
    }

    /**
     * @return mixed
     */
    protected function getExceptionClass()
    {
        return [BulkWriteException::class, RuntimeException::class];
    }

    /**
     * @return string
     */
    protected function getExpectedError()
    {
        $database = env('DQE_MONGO_DB_DATABASE');
        return "not authorized on $database to execute command { insert";
    }
}
