<?php

declare(strict_types=1);

namespace UI\Http\Common\Response\Violation;

final readonly class ViolationItem
{
    public function __construct(
        public string $source,
        public string $detail = '',
        public array $data = []
    ) {}
}
