<?php

declare(strict_types=1);

namespace Blog\Application\Post\Post\Command\Create\Command;

use SplFileInfo;

final readonly class ImageCommand
{
    public function __construct(
        public SplFileInfo $file,
        public string $extension,
        public string $originalFileName,
    ) {}
}
