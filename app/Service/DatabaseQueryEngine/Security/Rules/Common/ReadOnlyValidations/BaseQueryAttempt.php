<?php

namespace App\Service\DatabaseQueryEngine\Security\Rules\Common\ReadOnlyValidations;

use App\Service\DatabaseQueryEngine\Security\Exceptions\WrongDbConnectionPermissionsException;
use App\Service\DatabaseQueryEngine\Security\Rules\Contracts\DbQueryAttemptInterface;

/**
 * Class CreateAttempt
 * @package App\Service\DatabaseQueryEngine\Security\Rules\Sql\ReadOnlyValidations
 */
abstract class BaseQueryAttempt
{
    /**
     * @return bool
     * @throws WrongDbConnectionPermissionsException
     */
    public function run()
    {
        try {
            $this->runAction();
        } catch (\Exception $exception) {
            $exceptionClasses = $this->getExceptionClass();

            if (!is_array($exceptionClasses)) {
                $exceptionClasses = [$exceptionClasses];
            }

            foreach ($exceptionClasses as $expectedExceptionClass) {
                if ($exception instanceof $expectedExceptionClass) {
                    if (strpos($exception->getMessage(), $this->getExpectedError()) !== false) {
                        return true;
                    }
                    throw new WrongDbConnectionPermissionsException($this->getThrownError());
                }
            }
            $class = get_class($exception);
            throw new $class($exception->getMessage());
        }
    }

    /**
     * @return string
     */
    abstract protected function getModelClass();

    /**
     * @return string
     */
    abstract protected function getThrownError();

    /**
     * @return string
     */
    abstract protected function getExceptionClass();

    /**
     * @throws \Exception
     * @return mixed
     */
    abstract protected function runAction();

    /**
     * @return string
     */
    abstract protected function getExpectedError();
}