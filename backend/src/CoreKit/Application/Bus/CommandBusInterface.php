<?php

namespace CoreKit\Application\Bus;

interface CommandBusInterface
{
    public function dispatch(object $message): mixed;
}
