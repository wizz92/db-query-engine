<?php
namespace App\Service\DatabaseQueryEngine\Repositories\Contracts;

/**
 * Interface CustomQueryLogRepositoryInterface
 * @package App\Service\DatabaseQueryEngine\Repositories\Contracts
 */
interface RequestLogRepositoryInterface
{
    /**
     * @param $data
     * @return mixed
     */
    public function create($data);
}