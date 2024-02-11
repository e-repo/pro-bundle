<?php

declare(strict_types=1);

namespace Auth\User\Command\SignUp;

use Auth\User\Domain\Entity\EmailVo;
use Auth\User\Domain\Entity\IdVo;
use Auth\User\Domain\Entity\NameVo;
use Auth\User\Domain\Entity\Specification\UniqueEmailSpecification;
use Auth\User\Domain\Entity\User;
use Auth\User\Domain\Repository\UserRepositoryInterface;
use Auth\User\Domain\Service\PasswordHasher\Hasher;
use CoreKit\Application\Bus\CommandHandlerInterface;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private Hasher $hasher,
        private UserRepositoryInterface $userRepository,
        private UniqueEmailSpecification $uniqueEmailSpecification,
    ) {
    }

    public function __invoke(Command $command): void
    {
        $emailVo = new EmailVo($command->email);

        $user = new User(
            id: IdVo::next(),
            name: new NameVo($command->firstName),
            email: $emailVo,
            password: $command->password,
            registrationSource: $command->registrationSource,
            uniqueEmailSpecification: $this->uniqueEmailSpecification,
            hasher: $this->hasher,
        );

        $this->userRepository->add($user);
    }
}
