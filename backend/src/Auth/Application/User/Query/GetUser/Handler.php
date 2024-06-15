<?php

declare(strict_types=1);

namespace Auth\Application\User\Query\GetUser;

use Auth\Domain\User\Dto\UserProfileDto;
use Auth\Domain\User\Fetcher\UserFetcherInterface;
use CoreKit\Application\Bus\QueryHandlerInterface;

final readonly class Handler implements QueryHandlerInterface
{
    public function __construct(
        private UserFetcherInterface $userFetcher,
    ) {}

    public function __invoke(Query $query): UserProfileDto
    {
        return $this->userFetcher->getById($query->userId);
    }
}
