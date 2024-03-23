<?php

declare(strict_types=1);

namespace Auth\User\Domain\Entity;

use Ramsey\Uuid\Uuid;
use Stringable;

final readonly class IdVo implements Stringable
{
    public function __construct(
        public string $value
    ) {}

    public static function next(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
