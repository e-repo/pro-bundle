<?php

declare(strict_types=1);

namespace Service\Menu\Query\GetMenu;

final readonly class Query
{
    public function __construct(
        public string $name
    ) {}
}
