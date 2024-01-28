<?php

declare(strict_types=1);

namespace Auth\User\Query\User\GetUser;

final readonly class Query
{
    public function __construct(
        public string $userId,
    ) {
    }
}
