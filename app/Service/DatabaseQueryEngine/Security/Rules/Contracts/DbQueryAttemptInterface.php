<?php

namespace App\Service\DatabaseQueryEngine\Security\Rules\Contracts;

use App\Service\DatabaseQueryEngine\Security\Exceptions\WrongDbConnectionPermissionsException;

/**
 * Interface DbQueryAttemptInterface
 * @package App\Service\DatabaseQueryEngine\Security\Rules\Contracts
 */
interface DbQueryAttemptInterface
{
    /**
     * @throws WrongDbConnectionPermissionsException
     * @return mixed
     */
    public function run();
}