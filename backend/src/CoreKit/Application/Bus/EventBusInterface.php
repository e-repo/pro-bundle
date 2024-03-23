<?php

declare(strict_types=1);

namespace CoreKit\Application\Bus;

use Symfony\Contracts\EventDispatcher\Event;

interface EventBusInterface
{
    /**
     * @param Event $event
     * @return void
     */
    public function publish(Event $event): void;
}
