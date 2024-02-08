# Installation

```bash
composer require bitbirddev/trustkey-webhook-bundle
```

# Add configuration

### config/packages/trustkey_webhooks.yaml

```yaml
framework:
  webhook:
    routing:
      trustkey:
        service: "bitbirddev.webhook.request_parser.trustkey"
        secret: "YourTrustkeyWebhookSecret"
        # secret: "%env(TRUSTKEY_WEBHOOK_SECRET)%" # or use environment variable
```

# Example Consumer

### src/Webhooks/Consumer/TrustkeyWebhookConsumer.php

```php
<?php

namespace App\Webhooks\Consumer;

/*
 * In case Messages are delivered Async
 * make sure bin/console messenger:consume async is running
 */

use bitbirddev\TrustkeyWebhookBundle\Events\ActionPackEvent;
use bitbirddev\TrustkeyWebhookBundle\Events\ComponentEvent;
use bitbirddev\TrustkeyWebhookBundle\Events\ResourceEvent;
use bitbirddev\TrustkeyWebhookBundle\Events\SectionEvent;
use Symfony\Component\RemoteEvent\Attribute\AsRemoteEventConsumer;
use Symfony\Component\RemoteEvent\Consumer\ConsumerInterface;
use Symfony\Component\RemoteEvent\Exception\LogicException;
use Symfony\Component\RemoteEvent\RemoteEvent;

#[AsRemoteEventConsumer('trustkey')]
class TrustkeyWebhookConsumer implements ConsumerInterface
{
    public function consume(RemoteEvent $event): void
    {
        match ($event->getName()) {
            // Resources
            ResourceEvent::CREATED => $this->handle($event),
            ResourceEvent::UPDATED => $this->handle($event),
            ResourceEvent::ARCHIVED => $this->handle($event),

            // ActionPacks
            ActionPackEvent::LAUNCHED => $this->handle($event),
            ActionPackEvent::COMPLETE => $this->handle($event),
            ActionPackEvent::REOPEN => $this->handle($event),

            // Components
            ComponentEvent::PROGRESS_DONE => $this->handle($event),
            ComponentEvent::PROGRESS_REOPEN => $this->handle($event),

            // Sections
            SectionEvent::PROGRESS_DONE => $this->handle($event),
            SectionEvent::PROGRESS_REOPEN => $this->handle($event),

            default => throw new LogicException('Unhandled TrustkeyEvent: '.$event->getName())
        };
    }

    protected function handle(RemoteEvent $event): void
    {
        dd($event);
    }
}
```

# Example Json Payload

To test your Webhook you can use the following json payload. Send the payload via POST to `https://yourdomain/webhook/trustkey`

```json
{
  "event": "SECTION_PROGRESS_REOPEN",
  "webhook": "https://test.x.pipedream.net",
  "sectionSeriesId": "C-33",
  "completed": false,
  "id": "A-14538",
  "url": "https://test.trustkey.eu/view/board/actionpack/A-15266"
}
```
