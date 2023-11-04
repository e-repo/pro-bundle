<?php

declare(strict_types=1);

namespace UI\Http\Common\Response;

use UI\Http\Common\Response\Violation\ViolationItem;

final readonly class Violation
{
    public function __construct(
        public string $message,
        /** @var ViolationItem[] $errors */
        public array $errors = [],
    ) {}
}
