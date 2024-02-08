<?php

namespace bitbirddev\TrustkeyWebhookBundle\Events;

class ComponentEvent extends AbstractEvent
{
    const PROGRESS_DONE = 'COMPONENT_PROGRESS_DONE';

    const PROGRESS_REOPEN = 'COMPONENT_PROGRESS_REOPEN';
}
