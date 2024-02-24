<?php

declare(strict_types=1);

namespace Auth\User\Domain\Fetcher;

final class ListFilter
{
    public function __construct(
        public ?string $firstName,
        public ?string $lastName,
        public ?string $email,
        public ?string $role,
        public ?string $status,
    ) {
    }
}
