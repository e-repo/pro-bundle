<?php

declare(strict_types=1);

namespace Auth\User\Domain\Dto;

use DateTimeImmutable;

final readonly class GetUserDto
{
    public function __construct(
        public string $id,
        public string $firstName,
        public ?string $lastName,
        public string $email,
        public string $role,
        public string $status,
        public DateTimeImmutable $createdAt,
    ) {
    }
}
