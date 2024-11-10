<?php

declare(strict_types=1);

namespace Blog\Infra\Post\Fetcher;

use Blog\Domain\Post\Entity\CategoryDto;
use Blog\Domain\Post\Fetcher\CategoryFetcherInterface;
use Carbon\Carbon;
use CoreKit\Infra\BaseFetcher;
use Doctrine\DBAL\Exception;

final readonly class CategoryFetcher extends BaseFetcher implements CategoryFetcherInterface
{
    /**
     * @throws Exception
     */
    public function findById(string $id): ?CategoryDto
    {
        $qb = $this->createDBALQueryBuilder();

        $category = $qb
            ->select('*')
            ->from('blog.category', 'c')
            ->where(
                $qb->expr()->eq('c.id', ':categoryId')
            )
            ->setParameter('categoryId', $id)
            ->fetchAssociative();

        return $category ? $this->toCategoryDto($category) : null;
    }

    private function toCategoryDto(array $category): CategoryDto
    {
        return new CategoryDto(
            name: $category['name'],
            description: $category['description'],
            id: $category['id'],
            createdAt: Carbon::createFromFormat('Y-m-d H:i:sT', $category['created_at'])
                ?->toDateTimeImmutable(),
        );
    }
}
