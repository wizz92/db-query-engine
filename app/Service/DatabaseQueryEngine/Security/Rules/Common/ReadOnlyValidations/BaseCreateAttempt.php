<?php

namespace App\Service\DatabaseQueryEngine\Security\Rules\Common\ReadOnlyValidations;

use App\Service\DatabaseQueryEngine\Security\Exceptions\WrongDbConnectionPermissionsException;
use App\Service\DatabaseQueryEngine\Security\Rules\Contracts\DbQueryAttemptInterface;
use Illuminate\Database\QueryException;

/**
 * Class CreateAttempt
 * @package App\Service\DatabaseQueryEngine\Security\Rules\Sql\ReadOnlyValidations
 */
abstract class BaseCreateAttempt extends BaseQueryAttempt implements DbQueryAttemptInterface
{
    /**
     * @return string
     */
    protected function getThrownError()
    {
        return "Failed. User can create record";
    }

    /**
     * @throws \Exception
     * @return mixed|void
     */
    protected function runAction()
    {
        $class = $this->getModelClass();
        $insertTest = new $class;
        $insertTest->id = 1;
        $insertTest->save();
    }
}
