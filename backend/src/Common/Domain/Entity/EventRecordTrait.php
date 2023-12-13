<?php

namespace Common\Domain\Entity;

use Common\Domain\EventInterface;

trait EventRecordTrait
{
    /** @var EventInterface[] */
    private array $events = [];

    /**
     * @return EventInterface[]
     */
    public function getRecordedEvents(): array
    {
        return $this->events;
    }

    public function clearRecordedEvents(): void
    {
        $this->events = [];
    }

    public function record(EventInterface $event): void
    {
        $this->events[] = $event;
    }
}