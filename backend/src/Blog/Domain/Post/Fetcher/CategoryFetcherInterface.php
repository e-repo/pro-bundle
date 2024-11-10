<?php

declare(strict_types=1);

namespace Blog\Domain\Post\Fetcher;

use Blog\Domain\Post\Entity\CategoryDto;

interface CategoryFetcherInterface
{
    public function findById(string $id): ?CategoryDto;
}
