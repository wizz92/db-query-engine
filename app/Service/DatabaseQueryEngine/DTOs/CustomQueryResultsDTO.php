<?php

namespace App\Service\DatabaseQueryEngine\DTOs;

use App\Service\DatabaseQueryEngine\Models\Contracts\CustomQueryInterface;

/**
 * Class CustomQueryResultsDTO
 * @package App\Service\DatabaseQueryEngine\DTOs
 */
class CustomQueryResultsDTO
{
    protected $customQuery;
    protected $results;
    protected $queryLog;
    protected $requestLogId;

    /**
     * CustomQueryDTO constructor.
     * @param array $input
     */
    public function __construct(array $input)
    {
        $this->customQuery = $input['customQuery'];
        $this->results = $input['results'];
        $this->queryLog = $input['queryLog'];
        $this->requestLogId = $input['requestLogId'] ?? null;
    }

    /**
     * @return string
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @return integer
     */
    public function getRequestLogId()
    {
        return $this->requestLogId;
    }

    /**
     * @return CustomQueryInterface
     */
    public function getCustomQuery() : CustomQueryInterface
    {
        return $this->customQuery;
    }

    /**
     * @return string
     */
    public function getQueryLog()
    {
        return $this->queryLog;
    }

}