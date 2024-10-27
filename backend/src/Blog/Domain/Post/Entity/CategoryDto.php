<?php

declare(strict_types=1);

namespace Blog\Domain\Post\Entity;

final readonly class CategoryDto
{
    public function __construct(
        public string $name,
        public string $description,
        public ?string $id = null,
    ) {}
}
