<?php

namespace HcSync\Consumers;

interface Organization
{
    /**
     * @param array $event
     */
    function organizationCreated(array $event): bool;

    function organizationUpdated(array $event): bool;
}
