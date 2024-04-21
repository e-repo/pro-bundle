<?php

declare(strict_types=1);

namespace Auth\User\Command\ConfirmResetPassword;

final readonly class Command
{
    public function __construct(
        public string $token,
        public string $password
    ) {}
}
