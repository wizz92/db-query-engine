<?php
namespace App\Service\DatabaseQueryEngine\Security\Contracts;

/**
 * Interface RuleFactoryInterface
 * @package App\Service\DatabaseQueryEngine\Security\Contracts
 */
interface RulesResolverInterface
{
    /**
     * @param array $resolveParams
     * @return RuleInterface[]
     */
    public function resolve(array $resolveParams = []) : array ;
}