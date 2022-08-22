<?php

namespace App\Service\DatabaseQueryEngine\Invoker\Contracts;

/**
 * Interface FactoryInterface
 * @package App\Service\DatabaseQueryEngine\Invoker\Contracts
 */
interface FactoryInterface
{
    /**
     * @param $invokerType
     * @return InvokerInterface
     */
    public function create($invokerType): InvokerInterface;
}