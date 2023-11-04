<?php

declare(strict_types=1);

namespace UI\Http\Common\Exception\Resolver;

final readonly class ExceptionAttributes
{
    public function __construct(
        public int $code,
        public bool $hidden,
        public bool $loggable,
    ) {}

    public static function fromExceptionCode(int $code): self
    {
        return new self(
            code: $code,
            hidden: true,
            loggable: false
        );
    }
}
