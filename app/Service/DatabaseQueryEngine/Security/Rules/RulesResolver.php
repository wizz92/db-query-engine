<?php
namespace App\Service\DatabaseQueryEngine\Security\Rules;

use App\Service\DatabaseQueryEngine\Security\Contracts\AllowQueryExecuteRuleInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\AllowQueryStoreRuleInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\MongoDbRuleInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\MysqlRuleInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\RulesResolverInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\RuleInterface;
use App\Service\DatabaseQueryEngine\Security\Rules\Common\ApprovedQuery;
use App\Service\DatabaseQueryEngine\Security\Rules\Common\QueryTimeout;
use App\Service\DatabaseQueryEngine\Security\Rules\MongoDb\ConnectionReadOnly as MongoDbConnectionReadOnly;
use App\Service\DatabaseQueryEngine\Security\Rules\Sql\ConnectionReadOnly as SqlConnectionReadOnly;
use App\Service\DatabaseQueryEngine\Security\Rules\Sql\OnlyAllowedTypesQuery;

/**
 * Class Factory
 * @package App\Service\DatabaseQueryEngine\Security\Rules
 */
class RulesResolver implements RulesResolverInterface
{
    const RULE_TYPE_CLICKHOUSE = 3;
    const RULE_TYPE_MONGO = 2;
    const RULE_TYPE_MYSQL = 1;
    const RULE_TYPE_STORE = 'store';
    const RULE_TYPE_EXECUTE = 'execute';

    private $rules = [
        ApprovedQuery::class,
        QueryTimeout::class,
        MongoDbConnectionReadOnly::class,
        SqlConnectionReadOnly::class,
        OnlyAllowedTypesQuery::class
    ];

    /**
     * @var array
     */
    private $ruleTypes = [
        self::RULE_TYPE_EXECUTE => AllowQueryExecuteRuleInterface::class,
        self::RULE_TYPE_STORE => AllowQueryStoreRuleInterface::class,
        self::RULE_TYPE_MONGO => MongoDbRuleInterface::class,
        self::RULE_TYPE_MYSQL => MysqlRuleInterface::class,
    ];

    /**
     * @param array $resolveParams
     * @return RuleInterface[]
     */
    public function resolve(array $resolveParams = []): array
    {
        $rules = [];
        foreach ($this->rules as $rule) {
            $reflect = new \ReflectionClass($rule);
            foreach ($resolveParams as $param) {
                if($param != self::RULE_TYPE_CLICKHOUSE) {
                    if ($reflect->implementsInterface($this->ruleTypes[$param]) === false) {
                        continue 2;
                    }
                }
            }
            $rules[] = app()->make($rule);
        }

        return $rules;
    }
}
