<?php

declare(strict_types=1);

namespace Test\Functional\Auth\User\ChangeStatus;

use Auth\Domain\User\Entity\Status;
use Auth\Domain\User\Entity\User;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use JsonException;
use Test\Common\DataFromJsonResponseTrait;
use Test\Common\FunctionalTestCase;
use Test\Functional\Common\User\UserBuilder;

final class ChangeUserStatusTest extends FunctionalTestCase
{
    use DataFromJsonResponseTrait;

    private const ENDPOINT_URL = '/api/auth/v1/user/%s/status';

    private ReferenceRepository $referenceRepository;

    public function setUp(): void
    {
        parent::setUp();

        // arrange
        $this->referenceRepository = $this->databaseTool
            ->withPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE)
            ->loadFixtures([
                UserFixture::class,
            ])
            ->getReferenceRepository();
    }

    /**
     * @throws JsonException
     */
    public function testSuccessActivate(): void
    {
        // arrange
        $client = $this->createClient();

        $loadedUser = $this->referenceRepository->getReference(
            name: UserFixture::getPrefix(1),
            class: User::class
        );

        $expectedResponse = [
            'data' => [
                'status' => 'Статус пользователя успешно изменен.',
            ],
            'meta' => null,
        ];

        $client->loginUser(
            UserBuilder::createAdmin()->build()
        );

        // action
        $client->jsonRequest(
            method: 'PATCH',
            uri: $this->makeUrl($loadedUser->getId()->value),
            parameters: [
                'status' => 'active',
            ]
        );

        // assert
        $response = $this->getDataFromJsonResponse($client->getResponse());

        self::assertResponseIsSuccessful();
        self::assertEquals($expectedResponse, $response);
        self::assertEquals($loadedUser->getStatus()->value, Status::ACTIVE->value);
    }

    /**
     * @throws JsonException
     */
    public function testSuccessBlocked(): void
    {
        // arrange
        $client = $this->createClient();

        /** @var User $loadedUser */
        $loadedUser = $this->referenceRepository->getReference(
            name: UserFixture::getPrefix(1),
            class: User::class
        );

        $expectedResponse = [
            'data' => [
                'status' => 'Статус пользователя успешно изменен.',
            ],
            'meta' => null,
        ];

        $client->loginUser(
            UserBuilder::createAdmin()->build()
        );

        // action
        $client->jsonRequest(
            method: 'PATCH',
            uri: $this->makeUrl($loadedUser->getId()->value),
            parameters: [
                'status' => 'blocked',
            ]
        );

        // assert
        $response = $this->getDataFromJsonResponse($client->getResponse());

        self::assertResponseIsSuccessful();
        self::assertEquals($expectedResponse, $response);
        self::assertEquals($loadedUser->getStatus()->value, Status::BLOCKED->value);
    }

    private function makeUrl(string $id): string
    {
        return sprintf(self::ENDPOINT_URL, $id);
    }
}
