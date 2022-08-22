<?php

namespace App\Service\DatabaseQueryEngine\DTOs;

/**
 * Class CustomQueryDTO
 * @package App\Service\DatabaseQueryEngine\DTOs
 */
class CustomQueryDTO
{
    protected $name;

    /**
     * CustomQueryDTO constructor.
     * @param array $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['queryName'];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}