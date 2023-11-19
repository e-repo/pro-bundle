<?php

namespace Common\Application\Bus;

interface CommandBusInterface
{
    public function dispatch(object $message): mixed;
}