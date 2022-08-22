<?php

namespace App\Service\DatabaseQueryEngine\Security\Rules\MongoDb\ReadOnlyValidations;

use App\Service\DatabaseQueryEngine\Security\Rules\Contracts\DbQueryAttemptsContainerInterface;

/**
 * Class MongoDbQueryAttemptsContainer
 * @package App\Service\DatabaseQueryEngine\Security\Rules\MongoDb\ReadOnlyValidations
 */
class MongoDbQueryAttemptsContainer implements DbQueryAttemptsContainerInterface
{
    /**
     * @return array
     */
    public function get(): array
    {
        return [
            new CreateAttempt(),
            new DeleteAttempt(),
            new UpdateAttempt(),
        ];
    }
}