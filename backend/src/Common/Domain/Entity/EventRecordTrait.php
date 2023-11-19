<?php

namespace Common\Domain\Entity;

use Symfony\Contracts\EventDispatcher\Event;

trait EventRecordTrait
{
    /** @var Event[] */
    private array $events = [];

    /**
     * @return Event[]
     */
    public function getRecordedEvents(): array
    {
        return $this->events;
    }

    public function clearRecordedEvents(): void
    {
        $this->events = [];
    }

    public function record(Event $event): void
    {
        $this->events[] = $event;
    }
}