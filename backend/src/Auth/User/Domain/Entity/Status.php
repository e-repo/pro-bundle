<?php

namespace Auth\User\Domain\Entity;

enum Status: string
{
    case ACTIVE = 'active';
    case WAIT = 'wait';
    case BLOCKED = 'blocked';

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
