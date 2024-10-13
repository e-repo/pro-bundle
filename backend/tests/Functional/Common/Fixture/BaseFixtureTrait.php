<?php

declare(strict_types=1);

namespace Test\Functional\Common\Fixture;

trait BaseFixtureTrait
{
    public static function getReferenceName(string|int $key): string
    {
        return sprintf('%s_%s', self::getPrefix(), $key);
    }

    public static function allItems(): array
    {
        return [];
    }
}
