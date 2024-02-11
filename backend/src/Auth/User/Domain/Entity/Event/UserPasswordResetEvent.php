<?php

declare(strict_types=1);

namespace Auth\User\Domain\Entity\Event;

use CoreKit\Domain\Event\EventInterface;

final readonly class UserPasswordResetEvent implements EventInterface
{
    public function __construct(
        public string $email,
        public string $resetPasswordToken,
        public \DateTimeImmutable $passwordTokenExpires,
        public string $registrationSource,
    ) {
    }
}
