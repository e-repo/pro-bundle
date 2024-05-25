<?php

declare(strict_types=1);

namespace Auth\UI\Http\Auth\V1\User\RequestResetPassword;

use CoreKit\UI\Http\Response\ResponseInterface;

final class Response implements ResponseInterface
{
    public function __construct(
        public string $status,
    ) {}
}
