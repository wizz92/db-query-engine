<?php

namespace App\Service\DatabaseQueryEngine\Controllers;

use App\Http\Controllers\Controller;
use App\Service\DatabaseQueryEngine\Contracts\DatabaseQueryEngineInterface;
use App\Service\DatabaseQueryEngine\DTOs\CreateCustomQueryDTO;
use App\Service\DatabaseQueryEngine\DTOs\ExecuteCustomQueryDTO;
use App\Service\DatabaseQueryEngine\Models\CustomQuery;
use App\Service\DatabaseQueryEngine\Models\ReadOnlyTests\Mysql\TestDQE;
use App\Service\DatabaseQueryEngine\Repositories\Contracts\CustomQueryLogRepositoryInterface;
use App\Service\DatabaseQueryEngine\Repositories\Contracts\CustomQueryRepositoryInterface;
use App\Service\DatabaseQueryEngine\Security\Exceptions\SecurityException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Validator;
use App\Service\DatabaseQueryEngine\Exceptions\Exception as DatabaseQueryEngineException;
use Symfony\Component\HttpFoundation\Response as SymphonyResponse;
use App\Helpers\ResponseInterface;

/**
 * Class ApiTextController
 * @package App\Http\Controllers
 */
class CustomQueryController extends Controller
{
    /**
     * @var DatabaseQueryEngineInterface $databaseQueryEngine
     */
    private $databaseQueryEngine;

    /**
     * @var CustomQueryRepositoryInterface $customQueryRepository
     */
    private $customQueryRepository;

    /**
     * @var Request $request
     */
    private $request;

    private $response;

    /**
     * CustomQueryController constructor.
     * @param DatabaseQueryEngineInterface $databaseQueryEngine
     * @param CustomQueryRepositoryInterface $customQueryRepository
     * @param Request $request ,
     * @param ResponseInterface $response
     */
    public function __construct(
        DatabaseQueryEngineInterface $databaseQueryEngine,
        CustomQueryRepositoryInterface $customQueryRepository,
        Request $request,
        ResponseInterface $response
    ) {
        $this->databaseQueryEngine = $databaseQueryEngine;
        $this->customQueryRepository = $customQueryRepository;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index()
    {
        return $this
            ->response
            ->setData(false, $this->customQueryRepository->get())
            ->get();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function show($name)
    {
        $input = ['name' => $name];
        $validator = Validator::make($input, [
            'name' => 'required|exists:custom_queries,name'
        ]);

        if ($validator->fails()) {
            return $this->response->setErrors($validator->messages())->get();
        }
        return $this
            ->response
            ->setData(false, $this->customQueryRepository->findByName($name))
            ->get();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function execute()
    {
        $input = $this->request->all(['queryName', 'params', 'request_log_id']);
        $validator = Validator::make($input, [
            'queryName' => 'required|exists:custom_queries,name',
            'params' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->response->setErrors($validator->messages())->get();
        }

        try {
            $results = $this->databaseQueryEngine->executeQuery(new ExecuteCustomQueryDTO($input));
        } catch (SecurityException $exception) {
            return $this
                ->response
                ->setErrors($exception->getErrors())
                ->get();
        }

        return $this
            ->response
            ->setData(false, $results)
            ->get();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store()
    {
        $input = $this->request->all(['queryName', 'databaseType', 'query', 'description']);

        $validator = Validator::make($input, [
            'queryName' => 'required|unique:custom_queries,name',
            'databaseType' => 'required|in:1,2,3',
            'query' => 'required',
            'description' => 'required',
        ]);

        $input['creatorId'] = null;

        if ($validator->fails()) {
            return $this->response->setErrors($validator->messages())->get();
        }

        try {
            $query = $this->databaseQueryEngine->storeQuery(new CreateCustomQueryDTO($input));
        } catch (SecurityException $exception) {
            return $this
                ->response
                ->setErrors($exception->getErrors())
                ->get();
        }

        return $this
            ->response
            ->setAlerts('Query was successfully created')
            ->setData(false, $query)
            ->get();
    }

    /**
     * @param $id
     * @param $status
     * @return mixed
     */
    public function changeStatus($id, $status)
    {
        $validator = Validator::make(['id' => $id, 'status' => $status], [
            'id' => 'required|exists:custom_queries,id',
            'status' => 'required||in:2,4'
        ]);

        if ($validator->fails()) {
            return $this->response->setErrors($validator->messages())->get();
        }

        $query = $this->customQueryRepository->updateStatus(
            $this->customQueryRepository->find($id),
            $status
        );

        return $this
            ->response
            ->setData(false, $query)
            ->get();
    }

    /**
     * @param CustomQueryLogRepositoryInterface $customQueryLogRepository
     * @param $id
     * @return mixed
     */
    public function queryLogs(CustomQueryLogRepositoryInterface $customQueryLogRepository, $id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:custom_queries,id'
        ]);

        if ($validator->fails()) {
            return $this->response->setErrors($validator->messages())->get();
        }

        return $this
            ->response
            ->setData(false, $customQueryLogRepository->findForQuery($id, $this->request->get('limit')))
            ->get();
    }
}
