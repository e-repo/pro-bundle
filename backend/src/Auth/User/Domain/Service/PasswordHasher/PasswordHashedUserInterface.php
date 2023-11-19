<?php

namespace Auth\User\Domain\Service\PasswordHasher;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

interface PasswordHashedUserInterface extends PasswordAuthenticatedUserInterface
{
    public function changePlainPassword(string $passwordHash): void;
}