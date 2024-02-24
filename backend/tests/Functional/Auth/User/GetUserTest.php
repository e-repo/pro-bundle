<?php

declare(strict_types=1);

namespace Test\Functional\Auth\User;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Test\Common\FunctionalTestCase;
use Test\Functional\Auth\Common\Fixture\UserFixture;
use UI\Http\Common\DataFromJsonResponseTrait;

final class GetUserTest extends FunctionalTestCase
{
    use DataFromJsonResponseTrait;

    private const ENDPOINT_URL = '/api/auth/v1/user/%s';

    public function setUp(): void
    {
        parent::setUp();

        // arrange
        $this->databaseTool
            ->withPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE)
            ->loadFixtures([
                UserFixture::class,
            ]);

        $this->mailerListener->reset();
    }

    public function testSuccessGetUser(): void
    {
        // arrange
        $loadedUser = UserFixture::allItems()[1];
        $client = $this->createClient();

        // action
        $client->request(
            method: 'GET',
            uri: sprintf(self::ENDPOINT_URL, $loadedUser['id']),
        );

        $response = $this->getDataFromJsonResponse($client->getResponse());
        $responseData = $response['data'];

        // assert
        self::assertResponseIsSuccessful();
        self::assertNull($responseData['meta']);
        self::assertEquals($loadedUser['id'], $responseData['id']);
        self::assertEquals($loadedUser['firstName'], $responseData['firstName']);
        self::assertEquals($loadedUser['lastName'], $responseData['lastName']);
        self::assertEquals($loadedUser['email'], $responseData['email']);
        self::assertEquals('ROLE_USER', $responseData['role']);
        self::assertEquals($loadedUser['status'], $responseData['status']);
        self::assertNotNull($responseData['createdAt']);
    }

    public function testFailedByInvalidId(): void
    {
        // arrange
        $invalidUserId = '41e08435-0cde-4c40-8b28-8191dfae367b';
        $client = $this->createClient();

        $expectedResponse = [
            'message' => 'Ошибка бизнес-логики.',
            'errors' => [
                [
                    'detail' => sprintf("Пользователь по идентификатору '%s' не найден", $invalidUserId),
                    'source' => '',
                    'data' => []
                ]
            ]
        ];

        // action
        $client->request(
            method: 'GET',
            uri: sprintf(self::ENDPOINT_URL, $invalidUserId),
        );

        $response = $this->getDataFromJsonResponse($client->getResponse());

        // assert
        self::assertResponseIsUnprocessable();
        self::assertEquals($expectedResponse, $response);
    }
}
