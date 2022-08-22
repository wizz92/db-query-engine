<?php

namespace App\Service\DatabaseQueryEngine\Security\Rules\Common;

use App\Service\DatabaseQueryEngine\Security\Exceptions\WrongDbConnectionPermissionsException;
use App\Service\DatabaseQueryEngine\Security\Rules\Contracts\DbQueryAttemptsContainerInterface;
use App\Service\DatabaseQueryEngine\Security\Rules\Rule;

/**
 * Class ConnectionReadOnly
 * @package App\Service\DatabaseQueryEngine\Security\Rules\Common
 */
abstract class ConnectionReadOnly extends Rule
{
    /**
     * @var DbQueryAttemptsContainerInterface $dbQueryAttemptsContainer
     */
    private $dbQueryAttemptsContainer;

    /**
     * ConnectionReadOnly constructor.
     * @param DbQueryAttemptsContainerInterface $dbQueryAttemptsContainer
     */
    public function __construct(DbQueryAttemptsContainerInterface $dbQueryAttemptsContainer)
    {
        $this->dbQueryAttemptsContainer = $dbQueryAttemptsContainer;
    }

    /**
     * @param null $data
     * @return bool
     */
    public function isAllowed($data = null): bool
    {
        $queryAttempts = $this->dbQueryAttemptsContainer->get();

        foreach ($queryAttempts as $attempt) {
            try {
                $attempt->run();
            } catch (WrongDbConnectionPermissionsException $exception) {
                $this->setErrorMessage($exception->getMessage());
                return false;
            }
        }
        return true;
    }
}