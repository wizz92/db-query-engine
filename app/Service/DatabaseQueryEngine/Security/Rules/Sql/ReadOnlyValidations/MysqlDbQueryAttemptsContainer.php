<?php

namespace App\Service\DatabaseQueryEngine\Security\Rules\Sql\ReadOnlyValidations;

use App\Service\DatabaseQueryEngine\Security\Rules\Contracts\DbQueryAttemptInterface;
use App\Service\DatabaseQueryEngine\Security\Rules\Contracts\DbQueryAttemptsContainerInterface;

/**
 * Class MysqlDbQueryAttemptsContainer
 * @package App\Service\DatabaseQueryEngine\Security\Rules\Sql\ReadOnlyValidations
 */
class MysqlDbQueryAttemptsContainer implements DbQueryAttemptsContainerInterface
{

    /**
     * @return array | DbQueryAttemptInterface[]
     */
    public function get(): array
    {
        return [
            new AlterTableAttempt(),
            new CreateTableAttempt(),
            new CreateAttempt(),
            new DeleteAttempt(),
            new DropTableAttempt(),
            new UpdateAttempt(),
        ];
    }
}
