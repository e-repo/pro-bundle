<?php

declare(strict_types=1);

namespace UI\Http\Common\Response;

final class ResponseDataWrapper
{
    public function __construct(
        public ResponseInterface $data,
    ) {}
}
