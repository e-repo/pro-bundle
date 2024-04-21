<?php

declare(strict_types=1);

namespace Test\Functional\Common\Fixture;

trait BaseFixtureTrait
{
    private function getReferenceName(string $referencePrefix, string|int $key): string
    {
        return sprintf('%s_%s', $referencePrefix, $key);
    }

    public static function allItems(): array
    {
        return [];
    }
}
