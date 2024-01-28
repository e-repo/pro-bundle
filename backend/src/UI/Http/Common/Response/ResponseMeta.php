<?php

declare(strict_types=1);

namespace UI\Http\Common\Response;

final readonly class ResponseMeta
{
    public function __construct(
        public int $offset,
        public int $limit = 100,
        public int $total = 0,
    ) {
    }
}
