<?php

declare(strict_types=1);

namespace Auth\User\Domain\Entity\Event;

use Common\Domain\EventInterface;
use DateTimeImmutable;

final readonly class UserCreatedEvent implements EventInterface
{
    public function __construct(
        public string $id,
        public string $firstname,
        public ?string $lastname,
        public string $email,
        public ?string $emailConfirmToken,
        public string $status,
        public string $role,
        public DateTimeImmutable $createdAt,
    ) {
    }
}
