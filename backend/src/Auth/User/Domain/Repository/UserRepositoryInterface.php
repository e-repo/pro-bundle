<?php

namespace Auth\User\Domain\Repository;

use Auth\User\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function add(User $user): void;

    public function findByEmail(string $email): ?User;
}