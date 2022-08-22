<?php

namespace App\Service\DatabaseQueryEngine\Security\Rules\MongoDb;

use App\Service\DatabaseQueryEngine\Security\Contracts\AllowQueryExecuteRuleInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\MongoDbRuleInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\RuleInterface;
use App\Service\DatabaseQueryEngine\Security\Exceptions\NotAllowedQueryException;
use App\Service\DatabaseQueryEngine\Security\Rules\Rule;
use App\Service\DatabaseQueryEngine\Security\Rules\Common\ConnectionReadOnly as BaseConnectionReadOnly;

/**
 * Class ConnectionReadOnly
 * @package App\Service\DatabaseQueryEngine\Security\Rules\Sql
 */
class ConnectionReadOnly extends BaseConnectionReadOnly implements
    RuleInterface,
    AllowQueryExecuteRuleInterface,
    MongoDbRuleInterface
{

}