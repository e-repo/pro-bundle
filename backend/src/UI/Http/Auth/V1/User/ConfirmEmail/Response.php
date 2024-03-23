<?php

declare(strict_types=1);

namespace UI\Http\Auth\V1\User\ConfirmEmail;

use UI\Http\Common\Response\ResponseInterface;

final readonly class Response implements ResponseInterface
{
    public function __construct(
        public string $status,
    ) {}
}
