<?php

namespace App\Service\DatabaseQueryEngine\Security\Rules\Sql\ReadOnlyValidations;

use App\Service\DatabaseQueryEngine\Security\Exceptions\WrongDbConnectionPermissionsException;
use App\Service\DatabaseQueryEngine\Security\Rules\Contracts\DbQueryAttemptInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class AlterTableAttempt
 * @package App\Service\DatabaseQueryEngine\Security\Rules\Sql\ReadOnlyValidations
 */
class AlterTableAttempt implements DbQueryAttemptInterface
{
    /**
     * @return mixed|void
     * @throws WrongDbConnectionPermissionsException
     */
    public function run()
    {
        try {
            \Schema::connection('dqe_mysql')->table('test_dqe_read_only', function (Blueprint $table) {
                $table->increments('id');
            });
        } catch (QueryException $exception) {
            $exception->getMessage();
            if (strpos($exception->getMessage(), "ALTER command denied to user") !== false) {
                return true;
            }
        }

        throw new WrongDbConnectionPermissionsException('CHANGE TABLE action should not be allowed');
    }
}