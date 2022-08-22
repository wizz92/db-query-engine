<?php

namespace App\Service\DatabaseQueryEngine\Security\Rules\Sql\ReadOnlyValidations;

use App\Service\DatabaseQueryEngine\Models\ReadOnlyTests\Mysql\TestDQE;
use App\Service\DatabaseQueryEngine\Security\Rules\Common\ReadOnlyValidations\BaseCreateAttempt;
use App\Service\DatabaseQueryEngine\Security\Rules\Contracts\DbQueryAttemptInterface;
use Illuminate\Database\QueryException;

/**
 * Class CreateAttempt
 * @package App\Service\DatabaseQueryEngine\Security\Rules\Sql\ReadOnlyValidations
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
        return "INSERT command denied to user";
    }
}
