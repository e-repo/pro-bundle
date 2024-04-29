<?php

declare(strict_types=1);

namespace UI\Http\Service\V1\Menu;

final readonly class Response
{
    public function __construct(
        public string $id,
        public string $title,
        public string $icon,
    ) {}
}
