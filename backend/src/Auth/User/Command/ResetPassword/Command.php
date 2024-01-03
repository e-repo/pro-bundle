<?php

declare(strict_types=1);

namespace Auth\User\Command\ResetPassword;

final readonly class Command
{
    public function __construct(
        public string $email,
        public string $registrationSource,
    ) {
    }
}
