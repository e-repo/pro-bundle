<?php

declare(strict_types=1);

namespace Blog\Domain\Reader\Entity\Specification;

use Blog\Domain\Reader\Entity\Reader;
use Blog\Domain\Reader\Repository\ReaderRepositoryInterface;

final readonly class UniqueEmail
{
    public function __construct(
        private ReaderRepositoryInterface $readerRepository
    ) {}

    public function isSatisfiedBy(Reader $reader): bool
    {
        $user = $this->readerRepository->findByEmail(
            $reader->getEmail()->value
        );

        return null === $user;
    }
}
