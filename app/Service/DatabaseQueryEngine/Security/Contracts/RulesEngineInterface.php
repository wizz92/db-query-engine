<?php
namespace App\Service\DatabaseQueryEngine\Security\Contracts;

/**
 * Interface RulesEngineInterface
 * @package App\Service\DatabaseQueryEngine\Security\Contracts
 */
interface RulesEngineInterface
{
    /**
     * @param $type
     * @param $context
     * @return mixed
     */
    public function run($type, $context);

    /**
     * @param $data
     * @return RulesEngineInterface
     */
    public function setData($data) : RulesEngineInterface;

    /**
     * @return array
     */
    public function getErrors() : array;
}