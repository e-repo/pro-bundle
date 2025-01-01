<?php

declare(strict_types=1);

namespace CoreKit\Infra\FileStorage;

use Blog\Application\Common\FileStorage\MimeTypeInterface;
use Symfony\Component\Mime\MimeTypes;

final class MimeType implements MimeTypeInterface
{
    public function guessMimeType(string $path): ?string
    {
        return MimeTypes::getDefault()->guessMimeType($path);
    }
}
