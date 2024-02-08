<?php

namespace bitbirddev\TrustkeyWebhookBundle\Events;

use Symfony\Component\RemoteEvent\RemoteEvent;

abstract class AbstractEvent extends RemoteEvent
{
    private string $url;

    private string $webhook;

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setWebhook(string $webhook): void
    {
        $this->webhook = $webhook;
    }

    public function getWebhook(): string
    {
        return $this->webhook;
    }
}
