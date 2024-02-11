<?php

declare(strict_types=1);

namespace Auth\User\Command\ResetPassword;

use Auth\User\Domain\Repository\UserRepositoryInterface;
use CoreKit\Application\Bus\CommandHandlerInterface;
use DomainException;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function __invoke(Command $command): void
    {
        $user = $this->userRepository->findByEmail($command->email);

        if (null === $user) {
            throw new DomainException('Пользователь по указанному email не найден');
        }

        $user->resetPassword($command->registrationSource);
    }
}
