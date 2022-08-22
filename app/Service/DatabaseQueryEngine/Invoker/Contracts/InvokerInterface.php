<?php

namespace App\Service\DatabaseQueryEngine\Invoker\Contracts;

use App\Service\DatabaseQueryEngine\Models\Contracts\CustomQueryInterface;

/**
 * Interface InvokerInterface
 * @package App\Service\DatabaseQueryEngine\Contracts
 */
interface InvokerInterface
{
    /**
     * @param CustomQueryInterface $customQuery
     * @param null $params
     * @return mixed
     */
    public function execute(CustomQueryInterface $customQuery, $params = null);

    /**
     * @param $requestLogId
     * @return InvokerInterface
     */
    public function setRequestLogId($requestLogId) : InvokerInterface;
}