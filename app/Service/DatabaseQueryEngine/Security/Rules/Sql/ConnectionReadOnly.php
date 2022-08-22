<?php

namespace App\Service\DatabaseQueryEngine\Security\Rules\Sql;

use App\Service\DatabaseQueryEngine\Security\Contracts\AllowQueryExecuteRuleInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\MysqlRuleInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\RuleInterface;
use App\Service\DatabaseQueryEngine\Security\Exceptions\NotAllowedQueryException;
use App\Service\DatabaseQueryEngine\Security\Rules\Rule;
use App\Service\DatabaseQueryEngine\Security\Rules\Common\ConnectionReadOnly as BaseConnectionReadOnly;

/**
 * Class OnlyAllowedTypesQuery
 * @package App\Service\DatabaseQueryEngine\Security\Rules\Sql
 */
class ConnectionReadOnly extends BaseConnectionReadOnly implements
    RuleInterface,
    MysqlRuleInterface,
    AllowQueryExecuteRuleInterface
{

}