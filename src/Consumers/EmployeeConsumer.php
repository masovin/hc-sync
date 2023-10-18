<?php

namespace HcSync\Consumers;

interface EmployeeConsumer
{
    /**
     * @param array $event
     * @return bool
     */
    function employeeCreated(array $event): bool;

    /**
     * @param array $event
     * @return bool
     */
    function employeeUpdated(array $event): bool;

    /**
     * @param array $event
     * @return bool
     */
    function changeOrganization(array $event): bool;

    /**
     * @param array $event
     * @return bool
     */
    function changePosition(array $event): bool;

    /**
     * @param array $event
     * @return bool
     */
    function updatePosition(array $event): bool;

    /**
     * @param array $event
     * @return bool
     */
    function employeeActivated(array $event): bool;

    /**
     * @param array $event
     * @return bool
     */
    function employeeDisabled(array $event): bool;

    /**
     * @param array $event
     * @return bool
     */
    function employmentStatusCreated(array $event): bool;

    /**
     * @param array $event
     * @return bool
     */
    function employmentStatusUpdated(array $event): bool;

    /**
     * @param array $event
     * @return bool
     */
    function employmentStatusDeleted(array $event): bool;
}
