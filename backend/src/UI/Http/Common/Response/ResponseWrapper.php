<?php

declare(strict_types=1);

namespace UI\Http\Common\Response;

final class ResponseWrapper
{
    /**
     * @param ResponseInterface|ResponseInterface[] $data
     * @param ResponseMeta|null $meta
     */
    public function __construct(
        public ResponseInterface|array $data,
        public ?ResponseMeta $meta = null,
    ) {}
}
