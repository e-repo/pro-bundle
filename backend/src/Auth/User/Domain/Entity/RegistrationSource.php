<?php

declare(strict_types=1);

namespace Auth\User\Domain\Entity;

enum RegistrationSource: string
{
    case BLOG = 'blog';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
