<?php

declare(strict_types=1);

namespace Test\Functional\Auth\User;

use Auth\Domain\User\Entity\EmailVo;
use Auth\Domain\User\Entity\IdVo;
use Auth\Domain\User\Entity\NameVo;
use Auth\Domain\User\Entity\Specification\UniqueEmailSpecification;
use Auth\Domain\User\Entity\Status;
use Auth\Domain\User\Entity\User;
use Auth\Domain\User\Service\PasswordHasher\Hasher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Test\Functional\Common\Fixture\BaseFixtureTrait;

class BaseUserFixture extends Fixture
{
    use BaseFixtureTrait;

    public const NAME_PREFIX = 'user';

    public function __construct(
        private readonly Hasher $hasher,
        private readonly UniqueEmailSpecification $uniqueEmailSpecification,
    ) {}

    public function load(ObjectManager $manager): void
    {
        foreach (static::allItems() as $key => $item) {
            ++$key;

            $user = new User(
                id: new IdVo($item['id']),
                name: new NameVo(
                    first: $item['firstName'],
                    last: $item['lastName']
                ),
                email: new EmailVo($item['email']),
                password: $item['password'],
                registrationSource: $item['registrationSource'],
                uniqueEmailSpecification: $this->uniqueEmailSpecification,
                hasher: $this->hasher,
            );

            if ($item['status'] === Status::ACTIVE->value) {
                $user->confirmUserEmail($user->getEmailConfirmToken());
            }

            if ($item['status'] === Status::BLOCKED->value) {
                $user->block();
            }

            $manager->persist($user);

            $this->addReference($this->getReferenceName(self::NAME_PREFIX, $key), $user);
        }

        $manager->flush();
    }
}
