<?php

declare(strict_types=1);

namespace Functional\Auth\User;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\DBAL\Exception;
use JsonException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Test\Common\FunctionalTestCase;
use Test\Functional\Auth\Common\Fixture\UserFixture;
use UI\Http\Common\DataFromJsonResponseTrait;

final class ConfirmEmailTest extends FunctionalTestCase
{
    use DataFromJsonResponseTrait;

    private const ENDPOINT_URL = '/api/auth/v1/confirm-email';

    public function setUp(): void
    {
        parent::setUp();

        // arrange
        $this->databaseTool
            ->withPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE)
            ->loadFixtures([
                UserFixture::class,
            ]);
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function testSuccessConfirmEmail(): void
    {
        // arrange
        $loadedUser = UserFixture::allItems()[0];
        $client = $this->createClient();

        // action
        $client->jsonRequest(
            method: 'POST',
            uri: self::ENDPOINT_URL,
            parameters: [
                'userId' => $loadedUser['id'],
                'token' => $this->getConfirmTokenByUserId($loadedUser['id']),
            ]
        );

        // assert
        $changedUser = $this->getUserById($loadedUser['id']);

        $response = $this->getDataFromJsonResponse($client->getResponse());
        $responseData = $response['data'];

        self::assertResponseIsSuccessful();
        self::assertEquals('Email пользователя успешно подтвержден.', $responseData['status']);

        self::assertEquals('active', $changedUser['status']);
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function testFailedByInvalidConfirmToken(): void
    {
        // arrange
        $loadedUser = UserFixture::allItems()[0];
        $client = $this->createClient();

        // action
        $client->jsonRequest(
            method: 'POST',
            uri: self::ENDPOINT_URL,
            parameters: [
                'userId' => $loadedUser['id'],
                'token' => Uuid::uuid4()->toString(),
            ]
        );

        // assert
        $savedUser = $this->getUserById($loadedUser['id']);

        $response = $this->getDataFromJsonResponse($client->getResponse());
        $error = reset($response['errors']);

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        self::assertEquals('Передан не верный токен для подтверждения email.', $error['detail']);
        self::assertEquals('wait', $savedUser['status']);
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function testFailedByInvalidUserId(): void
    {
        // arrange
        $loadedUser = UserFixture::allItems()[0];
        $client = $this->createClient();

        $fakeUserId = Uuid::uuid4()->toString();

        // action
        $client->jsonRequest(
            method: 'POST',
            uri: self::ENDPOINT_URL,
            parameters: [
                'userId' => $fakeUserId,
                'token' => Uuid::uuid4()->toString(),
            ]
        );

        // assert
        $savedUser = $this->getUserById($loadedUser['id']);

        $response = $this->getDataFromJsonResponse($client->getResponse());
        $error = reset($response['errors']);

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        self::assertEquals(
            sprintf('Пользователь с идентификатором %s не найден.', $fakeUserId),
            $error['detail']
        );
        self::assertEquals('wait', $savedUser['status']);
    }

    /**
     * @throws Exception
     */
    private function getUserById(string $id): array
    {
        return $this->queryBuilder
            ->select('*')
            ->from('auth.user', 'u')
            ->where(
                $this->queryBuilder->expr()->eq('u.id', ':id')
            )
            ->setParameter('id', $id)
            ->fetchAssociative();
    }

    /**
     * @throws Exception
     */
    private function getConfirmTokenByUserId(string $userId): ?string
    {
        $user = $this->getUserById($userId);

        return $user['email_confirm_token'] ?? null;
    }
}
