<?php

declare(strict_types=1);

namespace Test\Functional\Auth\User;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\DBAL\Exception;
use JsonException;
use Symfony\Component\HttpFoundation\Response;
use Test\Common\FunctionalTestCase;
use Test\Functional\Auth\Common\Fixture\UserFixture;
use UI\Http\Common\DataFromJsonResponseTrait;

final class SignUpTest extends FunctionalTestCase
{
    use DataFromJsonResponseTrait;

    private const ENDPOINT_URL = '/api/auth/v1/sign-up';

    /**
     * @throws Exception
     * @throws JsonException
     */
    public function testSuccessSignUp(): void
    {
        // arrange
        $userEmail = 'test@test.ru';
        $userFirstName = 'Test';
        $registrationSource = 'blog';

        $expectedResponse = [
            'data' => [
                'status' => 'Пользователь создан успешно.'
            ]
        ];

        $client = $this->createClient();

        // action
        $client->jsonRequest(
            method: 'POST',
            uri: self::ENDPOINT_URL,
            parameters: [
                'firstName' => $userFirstName,
                'email' => $userEmail,
                'password' => 'secret',
                'registrationSource' => $registrationSource
            ]
        );

        $response = $this->getDataFromJsonResponse($client->getResponse());
        $user = $this->getUserByEmail($userEmail);

        // assert
        self::assertResponseIsSuccessful();
        self::assertEquals($expectedResponse, $response);

        self::assertEquals($userEmail, $user['email']);
        self::assertEquals('wait', $user['status']);
        self::assertEquals('ROLE_USER', $user['role']);
        self::assertEquals($userFirstName, $user['name_first']);
        self::assertEquals($registrationSource, $user['registration_source']);
        self::assertNotEmpty($user['password_hash']);
        self::assertNotEmpty($user['email_confirm_token']);

        self::assertEmailCount(1);

        $mail = self::getMailerMessage();
        $confirmEmailLink = sprintf(
            '/https?:[\/]{2}[a-z.]+\/confirm-email\?userId=%s&amp;token=%s/',
            $user['id'],
            $user['email_confirm_token']
        );

        self::assertMatchesRegularExpression($confirmEmailLink, $mail->getHtmlBody());
    }

    /**
     * @throws JsonException
     */
    public function testFailedEmailAlreadyExist(): void
    {
        // arrange
        $this->databaseTool
            ->withPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE)
            ->loadFixtures([
                UserFixture::class
            ]);

        $loadedUser = UserFixture::allItems()[0];
        $client = $this->createClient();

        $expectedResponse = [
            'message' => 'Ошибка бизнес-логики.',
            'errors' => [
                [
                    'detail' => 'Пользователь с данным email уже существует.',
                    'source' => '',
                    'data' => [
                        'email' => $loadedUser['email']
                    ]
                ]
            ]
        ];

        // action
        $client->jsonRequest(
            method: 'POST',
            uri: self::ENDPOINT_URL,
            parameters: [
                'firstName' => $loadedUser['firstName'],
                'email' => $loadedUser['email'],
                'password' => $loadedUser['password'],
                'registrationSource' => $loadedUser['registrationSource'],
            ]
        );

        // assert
        $response = $this->getDataFromJsonResponse($client->getResponse());

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        self::assertEquals($expectedResponse, $response);
    }

    /**
     * @throws Exception
     */
    private function getUserByEmail(string $email): array
    {
        return $this->queryBuilder
            ->select('*')
            ->from('auth.user', 'u')
            ->where(
                $this->queryBuilder->expr()->eq('u.email', ':email')
            )
            ->setParameter('email', $email)
            ->fetchAssociative();
    }
}
