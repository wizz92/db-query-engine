<?php
namespace App\Service\DatabaseQueryEngine\Log\Contracts;

use App\Service\DatabaseQueryEngine\Invoker\Contracts\ObservableInterface;

/**
 * Interface ObserverInterface
 * @package App\Service\DatabaseQueryEngine\Log\Contracts
 */
interface ObserverInterface
{
    /**
     * @param ObservableInterface $observable
     * @return mixed
     */
    public function update(ObservableInterface $observable);
}
