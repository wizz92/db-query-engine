<?php

namespace App\Service\DatabaseQueryEngine\Security\Rules\Common\ReadOnlyValidations;

use App\Service\DatabaseQueryEngine\Security\Exceptions\WrongDbConnectionPermissionsException;
use App\Service\DatabaseQueryEngine\Security\Rules\Contracts\DbQueryAttemptInterface;
use Illuminate\Database\QueryException;

/**
 * Class DeleteAttempt
 * @package App\Service\DatabaseQueryEngine\Security\Rules\Sql\ReadOnlyValidations
 */
abstract class BaseDeleteAttempt extends BaseQueryAttempt implements DbQueryAttemptInterface
{
    /**
     * @return string
     */
    protected function getThrownError()
    {
        return "Failed. User can delete record";
    }

    /**
     * @return mixed|void
     */
    protected function runAction()
    {
        $class = $this->getModelClass();
        $class::where('id', 1)->delete();
    }
}
