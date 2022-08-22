<?php

namespace App\Service\DatabaseQueryEngine\DTOs;

/**
 * Class CustomQueryDTO
 * @package App\Service\DatabaseQueryEngine\DTOs
 */
class CreateCustomQueryDTO extends CustomQueryDTO
{
    protected $databaseType;
    protected $query;
    protected $description;
    protected $creatorId;

    /**
     * CustomQueryDTO constructor.
     * @param array $input
     */
    public function __construct(array $input)
    {
        parent::__construct($input);
        $this->databaseType = $input['databaseType'];
        $this->query = $input['query'];
        $this->description = $input['description'];
        $this->creatorId = $input['creatorId'] ?? null;
    }

    /**
     * @return string
     */
    public function getDatabaseType()
    {
        return $this->databaseType;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getCreatorId()
    {
        return $this->creatorId;
    }
}
