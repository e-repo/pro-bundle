<?php

declare(strict_types=1);

namespace UI\Http\Auth\V1\User\GetUser;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class Request
{
    public function __construct(
        #[Assert\Uuid(message: 'Не валидный идентификатор пользователя.')]
        #[Assert\NotBlank(message: 'Не указан идентификатор пользователя.')]
        #[Assert\Length(min: 36, minMessage: 'Идентификатор пользователя не может быть менее 36 символов.')]
        public string $userId,
    ) {}
}
