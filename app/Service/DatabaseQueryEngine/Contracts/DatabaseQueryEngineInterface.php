<?php

namespace App\Service\DatabaseQueryEngine\Contracts;

use App\Service\DatabaseQueryEngine\Exceptions\Exception;
use App\Service\DatabaseQueryEngine\Models\Contracts\CustomQueryInterface;
use App\Service\DatabaseQueryEngine\Security\Exceptions\SecurityException;

/**
 * Interface DatabaseQueryEngineInterface
 * @package App\Service\DatabaseQueryEngine\Contracts
 */
interface DatabaseQueryEngineInterface
{
    /**
     * @param $data
     * @return CustomQueryInterface
     * @throws SecurityException
     */
    public function storeQuery($data): CustomQueryInterface;

    /**
     * @param $data
     * @return mixed
     * @throws SecurityException
     */
    public function executeQuery($data);
}