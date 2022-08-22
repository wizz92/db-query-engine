<?php

namespace App\Service\DatabaseQueryEngine\Security\Rules\MongoDb\ReadOnlyValidations;

use App\Service\DatabaseQueryEngine\Models\ReadOnlyTests\Mongodb\TestDQE;
use App\Service\DatabaseQueryEngine\Security\Rules\Contracts\DbQueryAttemptInterface;
use App\Service\DatabaseQueryEngine\Security\Rules\Common\ReadOnlyValidations\BaseDeleteAttempt;
use MongoDB\Driver\Exception\BulkWriteException;
use MongoDB\Driver\Exception\RuntimeException;

/**
 * Class DeleteAttempt
 * @package App\Service\DatabaseQueryEngine\Security\Rules\MongoDb\ReadOnlyValidations
 */
class DeleteAttempt extends BaseDeleteAttempt implements DbQueryAttemptInterface
{
    /**
     * @return string
     */
    protected function getModelClass()
    {
        return TestDQE::class;
    }

    /**
     * @return string
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
        return "not authorized on $database to execute command { delete";
    }
}
