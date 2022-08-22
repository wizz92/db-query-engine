<?php

namespace App\Service\DatabaseQueryEngine\Security\Rules\Sql;

use App\Service\DatabaseQueryEngine\DTOs\CreateCustomQueryDTO;
use App\Service\DatabaseQueryEngine\Security\Contracts\AllowQueryExecuteRuleInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\AllowQueryStoreRuleInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\MysqlRuleInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\RuleInterface;
use App\Service\DatabaseQueryEngine\Security\Exceptions\NotAllowedQueryException;
use App\Service\DatabaseQueryEngine\Security\Rules\Rule;

/**
 * Class OnlyAllowedTypesQuery
 * @package App\Service\DatabaseQueryEngine\Security\Rules\Sql
 */
class OnlyAllowedTypesQuery extends Rule implements
    RuleInterface,
    AllowQueryStoreRuleInterface,
    AllowQueryExecuteRuleInterface,
    MysqlRuleInterface
{
    /**
     * @var array
     */
    private $queryPatterns = [
        'INSERT INTO',
        'TRUNCATE ',
        'DELETE ',
        'UPDATE ',
        'DROP ',
        'CREATE VIEW',
        'CREATE TABLE',
        'CREATE DATABASE',
        'DROP TABLE',
        'ALTER TABLE'
    ];

    /**
     * @param CreateCustomQueryDTO $data
     * @return bool
     */
    public function isAllowed($data = null): bool
    {
        $query = $data->getQuery();
        foreach ($this->queryPatterns as $queryPattern) {
            if (strpos($query, strtolower($queryPattern)) !== false) {
                $this->setErrorMessage("Query contains not allowed pattern: {$queryPattern}");
                return false;
            }
        }
        return true;
    }
}