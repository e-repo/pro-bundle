<?php

declare(strict_types=1);

namespace CoreKit\Infra;

use CoreKit\Application\Bus\EventBusInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class EventBus implements EventBusInterface
{
    public function __construct(
        private MessageBusInterface $eventBus,
    ) {}

    public function publish(object $event): void
    {
        $this->eventBus->dispatch($event);
    }
}
