<?php

declare(strict_types=1);

namespace Blog\Domain\Post\Entity\Dto;

use Blog\Domain\Post\Entity\ImageType;
use CoreKit\Domain\Entity\Id;
use DateTimeImmutable;
use SplFileInfo;

final readonly class ImageDto
{
    public function __construct(
        public SplFileInfo $file,
        public string $originalFileName,
        public Id $fileKey,
        public ImageType $type,
        public ?DateTimeImmutable $createdAt = null
    ) {}
}
