<?php

declare(strict_types=1);

namespace Auth\User\Command\ConfirmEmail;

use Auth\User\Domain\Entity\IdVo;
use Auth\User\Domain\Repository\UserRepositoryInterface;
use Common\Application\Bus\CommandHandlerInterface;
use DomainException;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function __invoke(Command $command): void
    {
        $user = $this->userRepository->find(new IdVo($command->userId));

        if (null === $user) {
            throw new DomainException(
                sprintf('Пользователь с идентификатором %s не найден.', $command->userId)
            );
        }

        $user->confirmUserEmail($command->token);
    }
}
