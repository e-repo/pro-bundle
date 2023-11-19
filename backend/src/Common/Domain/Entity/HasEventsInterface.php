<?php

namespace Common\Domain\Entity;

use Symfony\Contracts\EventDispatcher\Event;

interface HasEventsInterface
{
    /**
     * @return Event[]
     */
    public function getRecordedEvents(): array;

    public function clearRecordedEvents(): void;
}