<?php

declare(strict_types=1);

namespace Auth\User\Domain\Entity;

use Stringable;
use Webmozart\Assert\Assert;

final readonly class EmailVo implements Stringable
{
    public function __construct(
        public string $value
    ) {
        Assert::email($this->value, 'Переданный email не является корректным email адресом.');
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
