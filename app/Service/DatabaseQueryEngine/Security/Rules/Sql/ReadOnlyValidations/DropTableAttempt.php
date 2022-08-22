<?php

namespace App\Service\DatabaseQueryEngine\Security\Rules\Sql\ReadOnlyValidations;

use App\Service\DatabaseQueryEngine\Security\Exceptions\WrongDbConnectionPermissionsException;
use App\Service\DatabaseQueryEngine\Security\Rules\Contracts\DbQueryAttemptInterface;
use Illuminate\Database\QueryException;

/**
 * Class DropTableAttempt
 * @package App\Service\DatabaseQueryEngine\Security\Rules\Sql\ReadOnlyValidations
 */
class DropTableAttempt implements DbQueryAttemptInterface
{

    /**
     * @return mixed|void
     * @throws WrongDbConnectionPermissionsException
     */
    public function run()
    {
        try {
            \Schema::connection('dqe_mysql')->dropIfExists('test_dqe_read_only');
        } catch (QueryException $exception) {
            $exception->getMessage();
            if (strpos($exception->getMessage(), "DROP command denied to user") !== false) {
                return true;
            }
        }

        throw new WrongDbConnectionPermissionsException('TABLE DROP action should not be allowed');
    }
}