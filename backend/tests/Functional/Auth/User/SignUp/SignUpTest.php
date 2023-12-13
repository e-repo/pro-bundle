<?php

declare(strict_types=1);

namespace Test\Functional\Auth\User\SignUp;

use JsonException;
use Symfony\Component\HttpFoundation\Response;
use Test\Common\FunctionalTestCase;
use Test\Functional\Auth\Common\Fixture\UserFixture;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\DBAL\Exception;
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

        $client = $this->createClient();

        // action
        $client->jsonRequest(
            method: 'POST',
            uri: self::ENDPOINT_URL,
            parameters: [
                'firstName' => $userFirstName,
                'email' => $userEmail,
                'password' => 'secret',
            ]
        );

        $response = $this->getDataFromJsonResponse($client->getResponse());
        $responseData = $response['data'];

        $user = $this->getUserByEmail($userEmail);

        // assert
        self::assertResponseIsSuccessful();
        self::assertEquals('Пользователь создан успешно.', $responseData['status']);

        self::assertEquals($userEmail, $user['email']);
        self::assertEquals('wait', $user['status']);
        self::assertEquals('ROLE_USER', $user['role']);
        self::assertEquals($userFirstName, $user['name_first']);
        self::assertNotEmpty($user['password_hash']);
        self::assertNotEmpty($user['email_confirm_token']);

        self::assertEmailCount(1);

        $mail = self::getMailerMessage();
        self::assertMatchesRegularExpression(
            '/<p>Для подтверждения вашей электронной почты при регистрации в сервисе <b>bunches\.shop<\/b> перейдите по ссылке:<\/p>/',
            $mail->getHtmlBody()
        );
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

        // action
        $client->jsonRequest(
            method: 'POST',
            uri: self::ENDPOINT_URL,
            parameters: [
                'firstName' => $loadedUser['firstName'],
                'email' => $loadedUser['email'],
                'password' => $loadedUser['password'],
            ]
        );

        // assert
        $response = $this->getDataFromJsonResponse($client->getResponse());
        $violation = reset($response['errors']);

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        self::assertEquals('Пользователь с данным email уже существует.', $violation['detail']);
        self::assertEquals($loadedUser['email'], $violation['data']['email']);
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
