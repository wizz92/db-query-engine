<?php

namespace App\Service\DatabaseQueryEngine\Invoker\Contracts;

use App\Service\DatabaseQueryEngine\Log\Contracts\ObserverInterface;
use App\Service\DatabaseQueryEngine\Models\Contracts\CustomQueryInterface;

/**
 * Interface ObservableInterface
 * @package App\Service\DatabaseQueryEngine\Invoker\Contracts
 */
interface ObservableInterface
{
    /**
     * @param ObserverInterface $observer
     * @return mixed
     */
    public function attach(ObserverInterface $observer);

    /**
     * @param ObserverInterface $observer
     * @return mixed
     */
    public function detach(ObserverInterface $observer);

    /**
     * @return mixed
     */
    public function getQueryResults();
}