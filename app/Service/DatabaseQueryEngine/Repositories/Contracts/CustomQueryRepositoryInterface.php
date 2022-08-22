<?php
namespace App\Service\DatabaseQueryEngine\Repositories\Contracts;

use App\Service\DatabaseQueryEngine\Models\Contracts\CustomQueryInterface;
use App\Service\DatabaseQueryEngine\Models\Contracts\CustomQueryLogInterface;

/**
 * Interface CustomQueryRepositoryInterface
 * @package App\Service\DatabaseQueryEngine\Repositories\Contracts
 */
interface CustomQueryRepositoryInterface
{
    /**
     * @param $data
     * @return mixed
     */
    public function create($data): CustomQueryInterface;

    /**
     * @param $name
     * @return CustomQueryInterface|null
     */
    public function findByName($name);

    /**
     * @param $id
     * @return CustomQueryInterface|null
     */
    public function find($id) : CustomQueryInterface;

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * @return mixed | CustomQueryInterface[]
     */
    public function get();

    /**
     * @param CustomQueryInterface $query
     * @param $status
     * @param null $moderatorId
     * @return CustomQueryInterface
     */
    public function updateStatus(CustomQueryInterface $query, $status, $moderatorId = null) : CustomQueryInterface;
}