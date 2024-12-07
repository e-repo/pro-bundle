<?php

declare(strict_types=1);

namespace Blog\Application\Post\Post\Command\Create;

use CoreKit\Application\Bus\CommandHandlerInterface;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct() {}

    public function __invoke(Command $command): void
    {
        // TODO: Implement __invoke() method.
    }
}
