<?php

namespace Auth\User\Domain\Entity;

enum Role: string
{
    case USER = 'ROLE_USER';
    case ADMIN = 'ROLE_ADMIN';

    public static function values(): array
    {
        return array_map(
            static function (self $case) {
                return $case->value;
            },
            self::cases()
        );
    }
}
