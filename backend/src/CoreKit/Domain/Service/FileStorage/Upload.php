<?php

declare(strict_types=1);

namespace CoreKit\Domain\Service\FileStorage;

use SplFileInfo;

final readonly class Upload
{
    public function __construct(
        public string $name,
        public string $key,
        public string $type,
        public string $extension,
        public SplFileInfo $file,
    ) {}
}
