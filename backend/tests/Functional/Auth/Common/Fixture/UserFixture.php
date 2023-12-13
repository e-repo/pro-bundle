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
                uniqueEmailSpecification: $this->uniqueEmailSpecification,
                hasher: $this->hasher,
                status: Status::tryFrom($item['status'])
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
                'status' => Status::WAIT->value,
            ],
            [
                'id' => '125d7cd7-0b77-409a-bc5c-19b44416a5fa',
                'firstName' => 'TestFirstName_2',
                'lastName' => 'TestLastName_2',
                'email' => 'test_2@test.ru',
                'password' => 'secret_2',
                'status' => Status::ACTIVE->value,
            ],
            [
                'id' => '283b5d61-dc29-47a6-bab1-e01114e4e56f',
                'firstName' => 'TestFirstName_3',
                'lastName' => 'TestLastName_3',
                'email' => 'test_3@test.ru',
                'password' => 'secret_3',
                'status' => Status::BLOCKED->value,
            ],
        ];
    }
}
