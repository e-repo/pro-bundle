<?php

declare(strict_types=1);

namespace Auth\User\Domain\Entity\Specification;

use Auth\User\Domain\Entity\User;
use Auth\User\Domain\Repository\UserRepositoryInterface;

final readonly class UniqueEmailSpecification
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function isSatisfiedBy(User $user): bool
    {
        $user  = $this->userRepository->findByEmail($user->getEmail());

        return null === $user;
    }
}
