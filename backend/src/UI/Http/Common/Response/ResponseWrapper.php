<?php

declare(strict_types=1);

namespace UI\Http\Common\Response;

final class ResponseWrapper
{
    public function __construct(
        public ResponseInterface $data,
        public ?ResponseMeta $meta = null,
    ) {}
}
