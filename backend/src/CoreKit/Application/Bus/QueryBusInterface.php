<?php

declare(strict_types=1);

namespace CoreKit\Application\Bus;

interface QueryBusInterface
{
    public function dispatch(object $message): mixed;
}
