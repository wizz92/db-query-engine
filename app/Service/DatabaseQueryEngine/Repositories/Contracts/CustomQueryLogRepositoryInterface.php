<?php
namespace App\Service\DatabaseQueryEngine\Repositories\Contracts;

/**
 * Interface CustomQueryLogRepositoryInterface
 * @package App\Service\DatabaseQueryEngine\Repositories\Contracts
 */
interface CustomQueryLogRepositoryInterface
{
    /**
     * @param int $queryId
     * @return int
     */
    public function getFailedAttemptsCount(int $queryId) : int;

    /**
     * @param $queryId
     * @param int $limit
     * @return mixed
     */
    public function findForQuery($queryId, $limit = 50);

    /**
     * @param $data
     * @return mixed
     */
    public function create($data);
}