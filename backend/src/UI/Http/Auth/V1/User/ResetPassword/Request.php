<?php

declare(strict_types=1);

namespace UI\Http\Auth\V1\User\ResetPassword;
use Auth\User\Domain\Entity\RegistrationSource;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use UI\Http\Common\Request\RequestPayloadInterface;

final class Request implements RequestPayloadInterface
{
    #[Assert\Length(
        max: 100,
        maxMessage: 'Email не может содержать более 100 символов.'
    )]
    #[Assert\Email(message: 'Значение не соответствует формату email.')]
    #[Assert\NotBlank(message: 'Email является обязательным полем для заполнения.')]
    #[OA\Property(example: 'alex@mail.ru')]
    public string $email;

    #[Assert\NotBlank(message: 'Источник сброса пароля является обязательным полем для заполнения.')]
    #[Assert\Choice(
        callback: [RegistrationSource::class, 'values'],
        message: 'Источник не существует.'
    )]
    #[OA\Property(example: 'blog')]
    public string $registrationSource;
}
