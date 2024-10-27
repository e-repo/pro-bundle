<?php

declare(strict_types=1);

namespace Blog\Domain\Post\Repository;

use Blog\Domain\Post\Entity\Category;

interface CategoryRepositoryInterface
{
    public function add(Category $category): void;
}
