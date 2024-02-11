<?php

declare(strict_types=1);

namespace UI\Http\Auth\V1\User\SignUp;

use Auth\User\Domain\Entity\RegistrationSource;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use UI\Http\Common\Request\RequestPayloadInterface;

final class Payload implements RequestPayloadInterface
{
    #[Assert\Length(
        min: 2,
        max: 200,
        minMessage: 'Имя не может содержать менее 2 символов.',
        maxMessage: 'Имя не может содержать более 200 символов.'
    )]
    #[Assert\NotBlank(message: 'Имя является обязательным полем для заполнения.')]
    #[OA\Property(example: 'Александр')]
    public string $firstName;

    #[Assert\Length(
        max: 100,
        maxMessage: 'Email не может содержать более 100 символов.'
    )]
    #[Assert\Email(message: 'Значение не соответствует формату email.')]
    #[Assert\NotBlank(message: 'Email является обязательным полем для заполнения.')]
    #[OA\Property(example: 'alex@mail.ru')]
    public string $email;

    #[Assert\Length(
        min: 6,
        max: 32,
        minMessage: 'Пароль может содержать менее 6 символов.',
        maxMessage: 'Пароль не может содержать более 32 символов.'
    )]
    #[Assert\NotBlank(message: 'Пароль является обязательным полем для заполнения.')]
    #[OA\Property(example: 'QwertY')]
    public string $password;

    #[Assert\NotBlank(message: 'Источник регистрации является обязательным полем для заполнения.')]
    #[Assert\Choice(
        callback: [RegistrationSource::class, 'values'],
        message: 'Источник регистрации не существует.'
    )]
    #[OA\Property(example: 'blog')]
    public string $registrationSource;
}
