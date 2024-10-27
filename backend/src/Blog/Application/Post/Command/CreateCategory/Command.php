<?php

declare(strict_types=1);

namespace Blog\Application\Post\Command\CreateCategory;

final readonly class Command
{
    public function __construct(
        public string $name,
        public string $description,
    ) {}
}
