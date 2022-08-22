<?php
namespace App\Service\DatabaseQueryEngine\Models\Contracts;

/**
 * Interface CustomQueryLogInterface
 * @package App\Service\DatabaseQueryEngine\Models\Contracts
 */
interface CustomQueryLogInterface
{
    /**
     * @return bool
     */
    public function isSuccessExecuted(): bool;

    /**
     * @return bool
     */
    public function isExecutionBlocked(): bool;

    /**
     * @return bool
     */
    public function isExecutionFailed(): bool;
}