<?php

declare(strict_types=1);

namespace Test\Functional\Auth\Common\Fixture;

use Auth\User\Domain\Entity\EmailVo;
use Auth\User\Domain\Entity\IdVo;
use Auth\User\Domain\Entity\NameVo;
use Auth\User\Domain\Entity\Specification\UniqueEmailSpecification;
use Auth\User\Domain\Entity\Status;
use Auth\User\Domain\Entity\User;
use Auth\User\Domain\Service\PasswordHasher\Hasher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

final class UserFixture extends Fixture
{
    public function __construct(
        private readonly Hasher $hasher,
        private readonly UniqueEmailSpecification $uniqueEmailSpecification,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::allItems() as $item) {
            $user = new User(
                id: new IdVo($item['id']),
                name: new NameVo(
                    first: $item['firstName'],
                    last: $item['lastName']
                ),
                email: new EmailVo($item['email']),
                password: $item['password'],
                host: 'localhost',
                uniqueEmailSpecification: $this->uniqueEmailSpecification,
                hasher: $this->hasher,
            );

            $manager->persist($user);
        }

        $manager->flush();
    }

    public static function allItems(): array
    {
        return [
            [
                'id' => 'f472d1a5-ba78-4039-94e3-ae0161256eaf',
                'firstName' => 'TestFirstName_1',
                'lastName' => 'TestLastName_1',
                'email' => 'test_1@test.ru',
                'password' => 'secret_1',
            ],
            [
                'id' => '125d7cd7-0b77-409a-bc5c-19b44416a5fa',
                'firstName' => 'TestFirstName_2',
                'lastName' => 'TestLastName_2',
                'email' => 'test_2@test.ru',
                'password' => 'secret_2',
            ],
        ];
    }
}
