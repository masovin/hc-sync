<?php

namespace HcSync\Consumers;

interface OrganizationConsumer
{
    /**
     * @param array $event
     * @return bool
     */
    function organizationCreated(array $event): bool;

    /**
     * @param array $event
     * @return bool
     */
    function organizationUpdated(array $event): bool;
}
