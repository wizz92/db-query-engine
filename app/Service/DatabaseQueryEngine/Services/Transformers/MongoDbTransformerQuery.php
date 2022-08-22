<?php

namespace App\Service\DatabaseQueryEngine\Services\Transformers;

use App\Service\DatabaseQueryEngine\Services\Transformers\Contracts\TransformerQueryInterface;
use MongoDB\BSON\UTCDateTime;

/**
 * Class MongoDbTransformerQuery
 * @package App\Service\DatabaseQueryEngine\Services\Transformers
 */
class MongoDbTransformerQuery implements TransformerQueryInterface
{
    private const FIELD_CREATED_AT = 'created_at';
    private const FIELD_UPDATED_AT = 'updated_at';
    private const FIELD_DELETED_AT = 'deleted_at';
    private const FIELD_STARTED_AT = 'started_at';
    private const FIELD_ENDED_AT   = 'ended_at';

    private const CONDITION_GREATER_THAN = '$gt';
    private const CONDITION_LESS_THAN = '$lt';

    /**
     * @var array
     */
    protected $queryBody;

    /**
     * @var array
     */
    protected $fieldsForResponse;

    /**
     * @param array $body
     *
     * @return \App\Service\DatabaseQueryEngine\Services\Transformers\Contracts\TransformerQueryInterface
     */
    public function setQueryBody(array $body): TransformerQueryInterface
    {
        $this->queryBody = $body;

        return $this;
    }

    /**
     * @param array $fieldsForResponse
     *
     * @return \App\Service\DatabaseQueryEngine\Services\Transformers\Contracts\TransformerQueryInterface
     */
    public function setFieldsForResponse(array $fieldsForResponse): TransformerQueryInterface
    {
        $this->fieldsForResponse = $fieldsForResponse;

        return $this;
    }

    /**
     * @return array
     */
    public function transform(): array
    {
        foreach ($this->getDateConditionsFieldNamesArr() as $fieldName) {
            $this->processCondition(
                $fieldName,
                static::CONDITION_GREATER_THAN);

            $this->processCondition(
                $fieldName,
                static::CONDITION_LESS_THAN);
        }

        return $this->queryBody;
    }

    /**
     * @param array $records
     *
     * @return array
     */
    public function getResponse(array $records): array
    {
        $result = [];
        foreach ($records as $key => $record) {
            $record = $record->getArrayCopy();

            $newRecord = [];
            foreach ($this->fieldsForResponse as $field) {
                $newRecord[$field] = $record[$field] ?? null;

                if (in_array($field, $this->getDateConditionsFieldNamesArr())) {
                    $newRecord[$field] = $record[$field]
                        ->toDateTime()
                        ->format('Y-m-d H:i:s');
                }
            }

            $result[$key] = $newRecord;
        }

        return $result;
    }

    /**
     * @param string $field
     * @param string $condition
     *
     * @return void
     */
    private function processCondition(string $field, string $condition): void
    {
        if (isset($this->queryBody[$field][$condition])) {
            $this->queryBody[$field][$condition] = new UTCDateTime($this->queryBody[$field][$condition]);
        }
    }

    /**
     * @return array|string[]
     */
    private function getDateConditionsFieldNamesArr(): array
    {
        return [
            static::FIELD_CREATED_AT,
            static::FIELD_UPDATED_AT,
            static::FIELD_DELETED_AT,
            static::FIELD_STARTED_AT,
            static::FIELD_ENDED_AT
        ];
    }
}
