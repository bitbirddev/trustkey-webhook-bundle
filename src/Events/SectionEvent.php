<?php

namespace bitbirddev\TrustkeyWebhookBundle\Events;

class SectionEvent extends AbstractEvent
{
    const PROGRESS_DONE = 'SECTION_PROGRESS_DONE';

    const PROGRESS_REOPEN = 'SECTION_PROGRESS_REOPEN';

    private string $sectionSeriesId;

    private bool $completed;

    public function setSectionSeriesId(string $sectionSeriesId): void
    {
        $this->sectionSeriesId = $sectionSeriesId;
    }

    public function getSectionSeriesId(): string
    {
        return $this->sectionSeriesId;
    }

    public function setCompleted(bool $completed): void
    {
        $this->completed = $completed;
    }

    public function getCompleted(): bool
    {
        return $this->completed;
    }
}
