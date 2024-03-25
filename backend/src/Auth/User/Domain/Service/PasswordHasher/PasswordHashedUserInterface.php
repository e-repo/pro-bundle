<?php

declare(strict_types=1);

namespace Auth\User\Domain\Service\PasswordHasher;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

interface PasswordHashedUserInterface extends PasswordAuthenticatedUserInterface
{
    public function changePlainPassword(string $passwordHash): void;
}
