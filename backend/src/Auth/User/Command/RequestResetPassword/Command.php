<?php

declare(strict_types=1);

namespace Auth\User\Command\RequestResetPassword;

final readonly class Command
{
    public function __construct(
        public string $email,
        public string $registrationSource,
    ) {}
}
