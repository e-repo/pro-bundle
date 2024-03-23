<?php

declare(strict_types=1);

namespace CoreKit\Domain\Entity;

use CoreKit\Domain\Event\EventInterface;

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
