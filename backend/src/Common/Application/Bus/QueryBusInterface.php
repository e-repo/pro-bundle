<?php

namespace Common\Application\Bus;

interface QueryBusInterface
{
    public function dispatch(object $message): mixed;
}
