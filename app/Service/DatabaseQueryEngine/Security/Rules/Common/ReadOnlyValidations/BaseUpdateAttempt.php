<?php

namespace App\Service\DatabaseQueryEngine\Security\Rules\Common\ReadOnlyValidations;

use App\Service\DatabaseQueryEngine\Security\Exceptions\WrongDbConnectionPermissionsException;
use App\Service\DatabaseQueryEngine\Security\Rules\Contracts\DbQueryAttemptInterface;
use Illuminate\Database\QueryException;

/**
 * Class BaseUpdateAttempt
 * @package App\Service\DatabaseQueryEngine\Security\Rules\Common\ReadOnlyValidations
 */
abstract class BaseUpdateAttempt extends BaseQueryAttempt implements DbQueryAttemptInterface
{
    /**
     * @return string
     */
    protected function getThrownError()
    {
        return "Failed. User can update record";
    }

    /**
     * @throws \Exception
     * @return mixed|void
     */
    protected function runAction()
    {
        $class = $this->getModelClass();
        $class::where('id', 1)->update(['id' => 2]);
    }
}