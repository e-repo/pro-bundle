<?php

declare(strict_types=1);

namespace Blog\Domain\Post\Repository;

use Blog\Domain\Post\Entity\Post;

interface PostRepositoryInterface
{
    public function add(Post $category): void;
}
