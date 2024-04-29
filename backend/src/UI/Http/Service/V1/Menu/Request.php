<?php

declare(strict_types=1);

namespace UI\Http\Service\V1\Menu;

use Symfony\Component\Validator\Constraints as Assert;
use UI\Http\Common\Request\RequestPayloadInterface;

final class Request implements RequestPayloadInterface
{
    #[Assert\NotNull(message: 'Не заполнено поле name.')]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Наименование не может содержать менее 2 символов.',
        maxMessage: 'Наименование не может содержать более 100 символов.'
    )]
    public string $name;
}
