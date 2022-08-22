<?php
namespace App\Service\DatabaseQueryEngine\Security\Rules;

use App\Service\DatabaseQueryEngine\Security\Contracts\AllowQueryExecuteRuleInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\AllowQueryStoreRuleInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\MongoDbRuleInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\MysqlRuleInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\RulesEngineInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\RulesResolverInterface;
use App\Service\DatabaseQueryEngine\Security\Contracts\RuleInterface;
use App\Service\DatabaseQueryEngine\Security\Rules\Common\ApprovedQuery;
use App\Service\DatabaseQueryEngine\Security\Rules\Sql\OnlyAllowedTypesQuery;
use App\Service\DatabaseQueryEngine\Security\Rules\Sql\ReadonlyPermissionDatabaseUser;

/**
 * Class Factory
 * @package App\Service\DatabaseQueryEngine\Security\Rules
 */
class RulesEngine implements RulesEngineInterface
{
    /**
     * @var RulesResolverInterface $resolver
     */
    private $resolver;

    /**
     * @var mixed $data
     */
    private $data;

    /**
     * @var array $errors
     */
    private $errors = [];

    /**
     * RulesEngine constructor.
     * @param RulesResolverInterface $rulesResolver
     */
    public function __construct(RulesResolverInterface $rulesResolver)
    {
        $this->resolver = $rulesResolver;
    }

    /**
     * @param $type
     * @param $context
     * @return mixed
     */
    public function run($type, $context)
    {

        $rules = $this->resolver->resolve([$type, $context]);

        foreach ($rules as $rule) {
            if ($rule->isAllowed($this->data) === false) {
                $this->errors[] = $rule->getErrorMessage();
            }
        }
    }

    /**
     * @param $data
     * @return RulesEngineInterface
     */
    public function setData($data): RulesEngineInterface
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}