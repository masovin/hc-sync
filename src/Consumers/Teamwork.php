<?php

namespace HcSync\Consumers;

interface Teamwork
{
    /**
     * @param array $event
     * @return bool
     */
    function teamworkCreated(array $event): bool;

    /**
     * @param array $event
     * @return bool
     */
    function teamworkUpdated(array $event): bool;

    /**
     * @param array $event
     * @return bool
     */
    function teamworkActivated(array $event): bool;

    /**
     * @param array $event
     * @return bool
     */
    function teamworkDeactivated(array $event): bool;

    /**
     * @param array $event
     * @return bool
     */
    function teamleaderActivated(array $event): bool;

    /**
     * @param array $event
     * @return bool
     */
    function teamleaderDeactivate(array $event): bool;

    /**
     * @param array $event
     * @return bool
     */
    function teammemberActivated(array $event): bool;

    /**
     * @param array $event
     * @return bool
     */
    function teammemberDeactivate(array $event): bool;
}
