<?php

declare(strict_types=1);

namespace Test\Functional\Auth\User\GetUserList;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Component\HttpFoundation\Response;
use Test\Common\DataFromJsonResponseTrait;
use Test\Common\FunctionalTestCase;

final class GetUserListTest extends FunctionalTestCase
{
    use DataFromJsonResponseTrait;

    private const ENDPOINT_URL = '/api/auth/v1/user/list';

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

    public function testSuccessGetUserList(): void
    {
        // arrange
        $offset = 0;
        $limit = 10;
        $client = $this->createClient();

        $expectedFields = [
            'id',
            'firstName',
            'lastName',
            'email',
            'role',
            'status',
            'createdAt',
        ];

        $expectedUserIds = array_map(
            static fn (array $user) => $user['id'],
            UserFixture::allItems()
        );

        // action
        $client->request(
            method: 'GET',
            uri: self::ENDPOINT_URL,
            parameters: [
                'offset' => $offset,
                'limit' => $limit,
            ]
        );

        $response = $this->getDataFromJsonResponse($client->getResponse());

        // assert
        self::assertResponseIsSuccessful();
        self::assertEquals($offset, $response['meta']['offset']);
        self::assertEquals($limit, $response['meta']['limit']);

        self::assertCount($response['meta']['total'], UserFixture::allItems());

        foreach ($response['data'] as $userResponse) {
            self::assertEqualsCanonicalizing($expectedFields, array_keys($userResponse));

            self::assertContains($userResponse['id'], $expectedUserIds);
        }
    }

    public function testSuccessGetUserByFirstNameFilter(): void
    {
        // arrange
        $offset = 0;
        $limit = 10;
        $client = $this->createClient();

        $expectedUserIds = [
            UserFixture::allItems()[0]['id'],
            UserFixture::allItems()[4]['id'],
        ];

        // action
        $client->request(
            method: 'GET',
            uri: self::ENDPOINT_URL,
            parameters: [
                'offset' => $offset,
                'limit' => $limit,
                'firstName' => 'TestFirstName_1',
            ]
        );

        $response = $this->getDataFromJsonResponse($client->getResponse());

        // assert
        self::assertResponseIsSuccessful();
        self::assertCount($response['meta']['total'], $expectedUserIds);
        self::assertCount(count($expectedUserIds), $response['data']);

        foreach ($response['data'] as $userResponse) {
            self::assertContains($userResponse['id'], $expectedUserIds);
        }
    }

    public function testSuccessGetUserByLastNameFilter(): void
    {
        // arrange
        $offset = 0;
        $limit = 10;
        $client = $this->createClient();

        $expectedUserIds = [
            UserFixture::allItems()[0]['id'],
            UserFixture::allItems()[4]['id'],
        ];

        // action
        $client->request(
            method: 'GET',
            uri: self::ENDPOINT_URL,
            parameters: [
                'offset' => $offset,
                'limit' => $limit,
                'lastName' => 'TestLastName_1',
            ]
        );

        $response = $this->getDataFromJsonResponse($client->getResponse());

        // assert
        self::assertResponseIsSuccessful();
        self::assertCount($response['meta']['total'], $expectedUserIds);
        self::assertCount(count($expectedUserIds), $response['data']);

        foreach ($response['data'] as $userResponse) {
            self::assertContains($userResponse['id'], $expectedUserIds);
        }
    }

    public function testSuccessGetUserByEmailFilter(): void
    {
        // arrange
        $offset = 0;
        $limit = 10;
        $client = $this->createClient();

        $expectedUserId = UserFixture::allItems()[1]['id'];

        // action
        $client->request(
            method: 'GET',
            uri: self::ENDPOINT_URL,
            parameters: [
                'offset' => $offset,
                'limit' => $limit,
                'email' => 'test_2@test.ru',
            ]
        );

        $response = $this->getDataFromJsonResponse($client->getResponse());

        // assert
        self::assertResponseIsSuccessful();
        self::assertEquals(1, $response['meta']['total']);
        self::assertCount(1, $response['data']);

        foreach ($response['data'] as $userResponse) {
            self::assertEquals($expectedUserId, $userResponse['id']);
        }
    }

    public function testSuccessGetUserByRoleFilter(): void
    {
        // arrange
        $offset = 0;
        $limit = 10;
        $client = $this->createClient();

        // action
        $client->request(
            method: 'GET',
            uri: self::ENDPOINT_URL,
            parameters: [
                'offset' => $offset,
                'limit' => $limit,
                'role' => 'ROLE_ADMIN',
            ]
        );

        $response = $this->getDataFromJsonResponse($client->getResponse());

        // assert
        self::assertResponseIsSuccessful();
        self::assertEquals(0, $response['meta']['total']);
        self::assertCount(0, $response['data']);
    }

    public function testSuccessGetUserByStatusFilter(): void
    {
        // arrange
        $offset = 0;
        $limit = 10;
        $client = $this->createClient();

        $expectedUserId = UserFixture::allItems()[2]['id'];

        // action
        $client->request(
            method: 'GET',
            uri: self::ENDPOINT_URL,
            parameters: [
                'offset' => $offset,
                'limit' => $limit,
                'status' => 'blocked',
            ]
        );

        $response = $this->getDataFromJsonResponse($client->getResponse());

        // assert
        self::assertResponseIsSuccessful();
        self::assertEquals(1, $response['meta']['total']);
        self::assertCount(1, $response['data']);

        foreach ($response['data'] as $userResponse) {
            self::assertEquals($expectedUserId, $userResponse['id']);
        }
    }

    public function testFailedWithoutOffset(): void
    {
        // arrange
        $limit = 10;
        $client = $this->createClient();

        // action
        $client->request(
            method: 'GET',
            uri: self::ENDPOINT_URL,
            parameters: [
                'limit' => $limit,
            ]
        );

        $response = $this->getDataFromJsonResponse($client->getResponse());
        $errors = $response['errors'];

        // assert
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        self::assertEquals('Не заполнено поле offset', $errors[0]['detail']);
    }

    public function testFailedWithoutLimit(): void
    {
        // arrange
        $offset = 0;
        $client = $this->createClient();

        // action
        $client->request(
            method: 'GET',
            uri: self::ENDPOINT_URL,
            parameters: [
                'offset' => $offset,
            ]
        );

        $response = $this->getDataFromJsonResponse($client->getResponse());
        $errors = $response['errors'];

        // assert
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        self::assertEquals('Не заполнено поле limit', $errors[0]['detail']);
    }
}
