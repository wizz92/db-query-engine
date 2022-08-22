<?php

namespace App\Service\DatabaseQueryEngine\Security\Rules\Sql\ReadOnlyValidations;

use App\Service\DatabaseQueryEngine\Models\ReadOnlyTests\Mysql\TestDQE;
use App\Service\DatabaseQueryEngine\Security\Rules\Common\ReadOnlyValidations\BaseDeleteAttempt;
use App\Service\DatabaseQueryEngine\Security\Rules\Contracts\DbQueryAttemptInterface;
use Illuminate\Database\QueryException;

/**
 * Class DeleteAttempt
 * @package App\Service\DatabaseQueryEngine\Security\Rules\Sql\ReadOnlyValidations
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
        return QueryException::class;
    }

    /**
     * @return string
     */
    protected function getExpectedError()
    {
        return "DELETE command denied to user";
    }
}
