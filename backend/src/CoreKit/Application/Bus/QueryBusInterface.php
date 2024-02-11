<?php

namespace CoreKit\Application\Bus;

interface QueryBusInterface
{
    public function dispatch(object $message): mixed;
}
