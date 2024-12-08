<?php

declare(strict_types=1);

namespace Blog\Domain\Common\FileStorage;

interface MimeTypeInterface
{
    public function guessMimeType(string $path): ?string;
}
