<?php

namespace App\Service\DatabaseQueryEngine\Invoker\Contracts;

use App\Service\DatabaseQueryEngine\Models\Contracts\CustomQueryInterface;

/**
 * Interface BaseInvokerInterface
 * @package App\Service\DatabaseQueryEngine\Invoker\Contracts
 */
interface BaseInvokerInterface extends InvokerInterface, ObservableInterface
{
    /**
     * @param ConcreteInvokerInterface $concreteInvoker
     * @return mixed
     */
    public function setConcreteInvoker(ConcreteInvokerInterface $concreteInvoker);
}