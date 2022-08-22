<?php
namespace App\Service\DatabaseQueryEngine\Models\Contracts;

/**
 * Interface CustomQueryInterface
 * @package App\Service\DatabaseQueryEngine\Models\Contracts
 */
interface CustomQueryInterface
{
    /**
     * @return bool
     */
    public function isApproved(): bool;

    /**
     * @return bool
     */
    public function isBlocked(): bool;

    /**
     * @return bool
     */
    public function isPendingApprove(): bool;

    /**
     * @return int
     */
    public function getType();

    /**
     * @return int
     */
    public function getStatus();

    /**
     * @return string
     */
    public function getQuery();

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @return mixed
     */
    public function getCreatorId();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return mixed
     */
    public function customQueryLogs();

    /**
     * @return mixed
     */
    public function addTimeoutIssue();

    /**
     * @return boolean
     */
    public function hasTimeoutsLimit(): bool;

    /**
     * @return mixed
     */
    public function flushTimeouts();
}