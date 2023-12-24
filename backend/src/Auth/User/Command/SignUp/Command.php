<?php

declare(strict_types=1);

namespace Auth\User\Command\SignUp;

final readonly class Command
{
    public function __construct(
        public string $firstName,
        public string $email,
        public string $password,
        public string $registrationSource,
    ) {
    }
}
