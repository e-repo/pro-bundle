<?php

declare(strict_types=1);

namespace Blog\Domain\Post\Entity\Dto;

use Blog\Domain\Post\Entity\Status;

final readonly class PostDto
{
    public function __construct(
        public string $slug,
        public string $title,
        public string $shortTitle,
        public string $content,
        public Status $status,
        public ImageDto $image,
        public ?string $id = null,
        public bool $commentAvailable = false,
        public ?MetadataDto $meta = null,
    ) {}
}
