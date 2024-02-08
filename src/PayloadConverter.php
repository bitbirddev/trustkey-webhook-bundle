<?php

namespace bitbirddev\TrustkeyWebhookBundle;

use bitbirddev\TrustkeyWebhookBundle\Events\AbstractEvent;
use bitbirddev\TrustkeyWebhookBundle\Events\ActionPackEvent;
use bitbirddev\TrustkeyWebhookBundle\Events\ComponentEvent;
use bitbirddev\TrustkeyWebhookBundle\Events\ResourceEvent;
use bitbirddev\TrustkeyWebhookBundle\Events\SectionEvent;
use Symfony\Component\RemoteEvent\Exception\ParseException;
use Symfony\Component\RemoteEvent\PayloadConverterInterface;

final class PayloadConverter implements PayloadConverterInterface
{
    public function convert(array $payload): AbstractEvent
    {
        if (! $payload['id']) {
            throw new ParseException("Missing 'id' in payload");
        }

        if (! $payload['event']) {
            throw new ParseException("Missing 'event' in payload");
        }

        if (\in_array($payload['event'], ['ACTION_PACK_LAUNCHED', 'ACTION_PACK_COMPLETE', 'ACTION_PACK_REOPEN'])) {
            $name = match ($payload['event']) {
                'ACTION_PACK_LAUNCHED' => ActionPackEvent::LAUNCHED,
                'ACTION_PACK_COMPLETE' => ActionPackEvent::COMPLETE,
                'ACTION_PACK_REOPEN' => ActionPackEvent::REOPEN,
            };
            $event = new ActionPackEvent(id: $payload['id'], name: $name, payload: $payload);
        } elseif (\in_array($payload['event'], ['SECTION_PROGRESS_REOPEN', 'SECTION_PROGRESS_DONE'])) {
            $name = match ($payload['event']) {
                'SECTION_PROGRESS_REOPEN' => SectionEvent::PROGRESS_REOPEN,
                'SECTION_PROGRESS_DONE' => SectionEvent::PROGRESS_DONE,
            };
            $event = new SectionEvent(id: $payload['id'], name: $name, payload: $payload);
            if (array_key_exists('sectionSeriesId', $payload)) {
                $event->setSectionSeriesId($payload['sectionSeriesId']);
            }
            if (array_key_exists('completed', $payload)) {
                $event->setCompleted($payload['completed']);
            }
        } elseif (\in_array($payload['event'], ['COMPONENT_PROGRESS_REOPEN', 'COMPONENT_PROGRESS_DONE'])) {
            $name = match ($payload['event']) {
                'COMPONENT_PROGRESS_REOPEN' => ComponentEvent::PROGRESS_REOPEN,
                'COMPONENT_PROGRESS_DONE' =>  ComponentEvent::PROGRESS_DONE,
            };
            $event = new ComponentEvent(id: $payload['id'], name: $name, payload: $payload);
        } elseif (\in_array($payload['event'], ['RESOURCE_CREATED', 'RESOURCE_UPDATED', 'RESOURCE_ARCHIVED'])) {
            $name = match ($payload['event']) {
                'RESOURCE_CREATED' => ResourceEvent::CREATED,
                'RESOURCE_UPDATED' => ResourceEvent::UPDATED,
                'RESOURCE_ARCHIVED' => ResourceEvent::ARCHIVED,
            };
            $event = new ResourceEvent(id: $payload['id'], name: $name, payload: $payload);
        } else {
            throw new ParseException('Invalid event type');
        }

        if (array_key_exists('url', $payload)) {
            $event->setUrl($payload['url']);
        }
        if (array_key_exists('webhook', $payload)) {
            $event->setWebhook($payload['webhook']);
        }

        return $event;
    }
}
