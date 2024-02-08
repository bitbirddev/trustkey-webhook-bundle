<?php

namespace bitbirddev\TrustkeyWebhookBundle\Events;

class ResourceEvent extends AbstractEvent
{
    const CREATED = 'RESOURCE_CREATED';

    const UPDATED = 'RESOURCE_UPDATED';

    const ARCHIVED = 'RESOURCE_ARCHIVED';
}
