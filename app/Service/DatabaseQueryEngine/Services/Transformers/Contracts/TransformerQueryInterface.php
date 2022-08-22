<?php

namespace App\Service\DatabaseQueryEngine\Services\Transformers\Contracts;

/**
 * Interface TransformerQueryInterface
 * @package App\Service\DatabaseQueryEngine\Services\Transformers\Contracts
 */
interface TransformerQueryInterface
{
    /**
     * @param array $body
     *
     * @return self
     */
    public function setQueryBody(array $body): self;

    /**
     * @param array $fields
     *
     * @return self
     */
    public function setFieldsForResponse(array $fields): self;

    /**
     * @param array $records
     *
     * @return array
     */
    public function getResponse(array $records): array;

    /**
     * @return array
     */
    public function transform(): array;
}
