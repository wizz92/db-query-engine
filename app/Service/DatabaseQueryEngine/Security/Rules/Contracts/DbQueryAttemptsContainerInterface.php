<?php

namespace App\Service\DatabaseQueryEngine\Security\Rules\Contracts;

/**
 * Interface DbQueryAttemptInterface
 * @package App\Service\DatabaseQueryEngine\Security\Rules\Contracts
 */
interface DbQueryAttemptsContainerInterface
{
    /**
     * @return array | DbQueryAttemptInterface[]
     */
    public function get() : array;
}