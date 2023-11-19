<?php

declare(strict_types=1);

namespace Auth\User\Domain\Entity\Event;

use Auth\User\Domain\Entity\Role;
use Auth\User\Domain\Entity\Status;
use DateTimeImmutable;
use Symfony\Contracts\EventDispatcher\Event;

final class UserCreatedEvent extends Event
{
    public function __construct(
        public readonly string $firstname,
        public readonly ?string $lastname,
        public readonly string $email,
        public readonly string $status,
        public readonly string $role,
        public readonly DateTimeImmutable $createdAt,
    ) {
    }
}
