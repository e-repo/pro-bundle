<?php

declare(strict_types=1);

namespace Blog\Domain\Post\Fetcher;

use Blog\Domain\Post\Entity\Dto\CategoryDto;

interface CategoryFetcherInterface
{
    public function findById(string $id): ?CategoryDto;

    /**
     * @return CategoryDto[]
     */
    public function findAllByName(?string $name, int $offset, int $limit): array;

    public function countByName(?string $name): int;
}
