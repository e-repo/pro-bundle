<?php

declare(strict_types=1);

namespace Blog\Domain\Post\Entity\Specification;

final readonly class SpecificationAggregator
{
    public function __construct(
        public UniqueName $uniqueNameSpecification,
    ) {}
}
