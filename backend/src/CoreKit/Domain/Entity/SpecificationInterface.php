<?php

declare(strict_types=1);

namespace CoreKit\Domain\Entity;

use Blog\Domain\Reader\Entity\Reader;

interface SpecificationInterface
{
    public function isSatisfiedBy(Reader $reader): bool;
}
