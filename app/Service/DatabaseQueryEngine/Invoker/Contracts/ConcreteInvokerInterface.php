<?php

namespace App\Service\DatabaseQueryEngine\Invoker\Contracts;

use App\Service\DatabaseQueryEngine\Models\Contracts\CustomQueryInterface;

/**
 * Interface InvokerInterface
 * @package App\Service\DatabaseQueryEngine\Contracts
 */
interface ConcreteInvokerInterface
{
    /**
     * @return \Closure
     */
    public function getProvider() : \Closure;

    /**
     * @return mixed
     */
    public function getConnection();
}