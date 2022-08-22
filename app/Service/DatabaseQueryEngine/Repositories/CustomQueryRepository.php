<?php

namespace App\Service\DatabaseQueryEngine\Repositories;

use App\Models\User;
use App\Service\DatabaseQueryEngine\Models\Contracts\CustomQueryInterface;
use App\Service\DatabaseQueryEngine\Models\CustomQuery;
use App\Service\DatabaseQueryEngine\Repositories\Contracts\CustomQueryRepositoryInterface;
use Illuminate\Database\DatabaseManager;

/**
 * Class CustomQueryRepository
 * @package App\Service\DatabaseQueryEngine\Repositories
 */
class CustomQueryRepository extends Repository implements CustomQueryRepositoryInterface
{
    /**
     * @var string
     */
    protected $modelClass = CustomQuery::class;

    /**
     * @var DatabaseManager $databaseManager
     */
    protected $databaseManager;

    /**
     * CustomQueryRepository constructor.
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function create($data): CustomQueryInterface
    {
        // $data['status'] = (int) auth()->id() === User::CUSTOM_QUERIES_MANAGER
        //     ? CustomQuery::STATUS_APPROVED
        //     : CustomQuery::STATUS_PENDING_APPROVE;

        $data['status'] = 2;

        return ($this->getModelClass())::create($data);
    }

    /**
     * @param $name
     * @return CustomQueryInterface|null
     */
    public function findByName($name)
    {
        return ($this->modelClass)::where('name', $name)->first();
    }

    /**
     * @return mixed | CustomQueryInterface[]
     */
    public function get()
    {
        return ($this->modelClass)::orderBy('id', 'desc')->get();
    }

    /**
     * @param CustomQueryInterface $query
     * @param $status
     * @param null $moderatorId
     * @return CustomQueryInterface
     * @throws \Throwable
     */
    public function updateStatus(CustomQueryInterface $query, $status, $moderatorId = null): CustomQueryInterface
    {
        $query->status = $status;
        $query->moderated_by = $moderatorId;
        $query->save();
        return $query;
    }

    /**
     * @param $id
     * @return mixed|void
     */
    public function delete($id)
    {
        $query = $this->find($id);
        if ($query) {
            $query->delete();
        }
    }

    /**
     * @param $id
     * @return CustomQueryInterface
     */
    public function find($id) : CustomQueryInterface
    {
        return ($this->modelClass)::find($id);
    }
}
