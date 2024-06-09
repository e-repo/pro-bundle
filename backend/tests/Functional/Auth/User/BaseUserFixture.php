<?php

declare(strict_types=1);

namespace Test\Functional\Auth\User;

use Auth\Domain\User\Entity\EmailVo;
use Auth\Domain\User\Entity\IdVo;
use Auth\Domain\User\Entity\NameVo;
use Auth\Domain\User\Entity\Role;
use Auth\Domain\User\Entity\Specification\UniqueEmailSpecification;
use Auth\Domain\User\Entity\Status;
use Auth\Domain\User\Entity\User;
use Auth\Domain\User\Service\PasswordHasher\Hasher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Test\Functional\Common\Fixture\BaseFixtureTrait;
use Test\Functional\Common\Fixture\PrefixableInterface;

class BaseUserFixture extends Fixture implements PrefixableInterface
{
    use BaseFixtureTrait;

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

            if (isset($item['status'])) {
                $user->changeStatus(
                    status: Status::from($item['status']),
                    changedBy: $user->getEmail()->value
                );
            }

            if (isset($item['role'])) {
                $user->changeRole(Role::from($item['role']));
            }

            $manager->persist($user);

            $this->addReference(self::getPrefix($key), $user);
        }

        $manager->flush();
    }

    public static function getPrefix(string|int $key): string
    {
        return self::makeReferenceName('user', $key);
    }
}
