<?php
namespace App\Service\DatabaseQueryEngine\Security\Contracts;

/**
 * Class RuleInterface
 * @package App\Service\DatabaseQueryEngine\Security\Contracts
 */
interface RuleInterface
{
    /**
     * @throws
     * @param mixed $data
     * @return bool
     */
    public function isAllowed($data = null) : bool;

    /**
     * @return mixed
     */
    public function getErrorMessage();
}