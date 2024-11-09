<?php

declare(strict_types=1);

namespace Test\Functional\Blog\Api\Post\CreateCategory;

use Test\Functional\Common\Fixture\Blog\BaseCategoryFixture;

final class CategoryFixture extends BaseCategoryFixture
{
    public static function allItems(): array
    {
        return [
            [
                'id' => '28912aa1-96ee-4631-8e34-14cd2f019e53',
                'name' => 'Регуляторы роста',
                'description' => 'Категория регуляторы роста содержит статьи на тему...',
            ],
        ];
    }
}
