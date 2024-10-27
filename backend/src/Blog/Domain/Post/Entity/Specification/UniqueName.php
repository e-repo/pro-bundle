<?php

declare(strict_types=1);

namespace Blog\Domain\Post\Entity\Specification;

use Blog\Domain\Post\Entity\Category;
use Blog\Domain\Post\Repository\CategoryRepositoryInterface;

final readonly class UniqueName
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
    ) {}

    public function isSatisfiedBy(Category $category): bool
    {
        $category = $this->categoryRepository->findByName(
            name: $category->getName()
        );

        return null === $category;
    }
}
