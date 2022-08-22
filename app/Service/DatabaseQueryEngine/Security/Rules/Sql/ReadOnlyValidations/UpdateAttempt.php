<?php

namespace App\Service\DatabaseQueryEngine\Security\Rules\Sql\ReadOnlyValidations;

use App\Service\DatabaseQueryEngine\Models\ReadOnlyTests\Mysql\TestDQE;
use App\Service\DatabaseQueryEngine\Security\Exceptions\WrongDbConnectionPermissionsException;
use App\Service\DatabaseQueryEngine\Security\Rules\Contracts\DbQueryAttemptInterface;
use App\Service\DatabaseQueryEngine\Security\Rules\Common\ReadOnlyValidations\BaseUpdateAttempt;
use Illuminate\Database\QueryException;

/**
 * Class CreateAttempt
 * @package App\Service\DatabaseQueryEngine\Security\Rules\Sql\ReadOnlyValidations
 */
class UpdateAttempt extends BaseUpdateAttempt implements DbQueryAttemptInterface
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
        return QueryException::class;
    }

    /**
     * @return string
     */
    protected function getExpectedError()
    {
        return "UPDATE command denied to user";
    }
}