<?php

namespace App\Service\DatabaseQueryEngine;

use App\Service\DatabaseQueryEngine\Contracts\DatabaseQueryEngineInterface;
use App\Service\DatabaseQueryEngine\DTOs\CreateCustomQueryDTO;
use App\Service\DatabaseQueryEngine\DTOs\ExecuteCustomQueryDTO;
use App\Service\DatabaseQueryEngine\Exceptions\BadArgumentsException;
use App\Service\DatabaseQueryEngine\Models\Contracts\CustomQueryInterface;
use App\Service\DatabaseQueryEngine\Log\Contracts\QueryLoggerInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\SecurityProxyInterface;

/**
 * Class DatabaseQueryEngine
 * @package App\Service\DatabaseQueryEngine
 */
class DatabaseQueryEngine implements DatabaseQueryEngineInterface
{
    /**
     * @var QueryLoggerInterface
     */
    private $logger;

    /**
     * @var SecurityProxyInterface
     */
    private $securityProxy;

    /**
     * DatabaseQueryEngine constructor.
     * @param QueryLoggerInterface $queryLogger
     * @param SecurityProxyInterface $securityProxy
     */
    public function __construct(
        QueryLoggerInterface $queryLogger,
        SecurityProxyInterface $securityProxy
    ) {
        $this->logger = $queryLogger;
        $this->securityProxy = $securityProxy;
    }

    /**
     * @param $data
     * @param $type
     * @return $this
     * @throws BadArgumentsException
     */
    private function ensureDataObject($data, $type)
    {
        if ($data instanceof $type == false) {
            $givenType = is_object($data) ?  get_class($data) : gettype($data);
            throw new BadArgumentsException("Input type should be instance of $type. $givenType given");
        }
        return $this;
    }

    /**
     * @param $data
     * @return CustomQueryInterface
     * @throws BadArgumentsException
     * @throws Exceptions\Exception
     */
    public function storeQuery($data): CustomQueryInterface
    {
        return $this
            ->ensureDataObject($data, CreateCustomQueryDTO::class)
            ->securityProxy
            ->storeQuery($data);
    }

    /**
     * @param $data
     * @return mixed
     * @throws BadArgumentsException
     * @throws Exceptions\Exception
     */
    public function executeQuery($data)
    {
        return $this
            ->ensureDataObject($data, ExecuteCustomQueryDTO::class)
            ->securityProxy
            ->executeQuery($data);
    }
}
