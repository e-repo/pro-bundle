<?php

declare(strict_types=1);

namespace Test\Functional\Common\Fixture;

interface PrefixableInterface
{
    public static function getPrefix(string|int $key): string;
}
