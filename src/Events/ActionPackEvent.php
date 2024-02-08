<?php

namespace bitbirddev\TrustkeyWebhookBundle\Events;

class ActionPackEvent extends AbstractEvent
{
    // const CREATED = 'ACTION_PACK_CREATED';
    //
    // const UPDATED = 'ACTION_PACK_UPDATED';
    //
    // const ARCHIVED = 'ACTION_PACK_ARCHIVED';

    const LAUNCHED = 'ACTION_PACK_LAUNCHED';

    const COMPLETE = 'ACTION_PACK_COMPLETE';

    const REOPEN = 'ACTION_PACK_REOPEN';

    const COMPONENT_PROGRESS_DONE = 'COMPONENT_PROGRESS_DONE';

    const COMPONENT_PROGRESS_REOPEN = 'COMPONENT_PROGRESS_REOPEN';

    const SECTION_PROGRESS_DONE = 'SECTION_PROGRESS_DONE';

    const SECTION_PROGRESS_REOPEN = 'SECTION_PROGRESS_REOPEN';
}
