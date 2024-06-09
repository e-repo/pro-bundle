<?php

declare(strict_types=1);

namespace Auth\Application\User\Command\ChangeStatus;

use Auth\Domain\User\Entity\Status;
use Auth\Domain\User\Repository\UserRepositoryInterface;
use CoreKit\Application\Bus\CommandHandlerInterface;
use DomainException;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function __invoke(Command $command): void
    {
        $user = $this->userRepository->find($command->id);

        if (null === $user) {
            throw new DomainException(
                sprintf('Пользователь по идентификатору \'%s\' не найден.', $command->id)
            );
        }

        $status = Status::tryFrom($command->status);

        if (null === $status) {
            throw new DomainException('Передан не допустимый статус для изменения.');
        }

        $user->changeStatus($status, $command->changedBy);
    }
}
