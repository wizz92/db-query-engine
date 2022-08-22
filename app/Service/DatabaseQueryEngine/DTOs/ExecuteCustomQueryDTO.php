<?php

namespace App\Service\DatabaseQueryEngine\DTOs;

/**
 * Class ExecuteCustomQueryDTO
 * @package App\Service\DatabaseQueryEngine\DTOs
 */
class ExecuteCustomQueryDTO extends CustomQueryDTO
{
    protected $params;
    protected $request_log_id;

    /**
     * CustomQueryDTO constructor.
     * @param array $input
     */
    public function __construct(array $input)
    {
        parent::__construct($input);
        $this->params = $input['params'];
        $this->request_log_id = $input['request_log_id'] ?? null;
    }

    /**
     * @return string
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return integer
     */
    public function getRequestLogId()
    {
        return $this->request_log_id;
    }
}
