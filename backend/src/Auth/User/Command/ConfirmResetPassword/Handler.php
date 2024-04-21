<?php

declare(strict_types=1);

namespace Auth\User\Command\ConfirmResetPassword;

use Auth\User\Domain\Repository\UserRepositoryInterface;
use Auth\User\Domain\Service\PasswordHasher\Hasher;
use CoreKit\Application\Bus\CommandHandlerInterface;
use DomainException;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private Hasher $hasher,
    ) {}

    public function __invoke(Command $command): void
    {
        $user = $this->userRepository->findByResetPasswordToken($command->token);

        if (null === $user) {
            throw new DomainException('Пользователь по указанному токену не найден');
        }

        $user->confirmResetPassword(
            token: $command->token,
            password: $command->password,
            hasher: $this->hasher
        );
    }
}
