<?php

declare(strict_types=1);

namespace Auth\User\Query\GetUser;

use Auth\User\Domain\Dto\GetUserDto;
use Auth\User\Domain\Fetcher\UserFetcherInterface;
use CoreKit\Application\Bus\QueryHandlerInterface;

final readonly class Handler implements QueryHandlerInterface
{
    public function __construct(
        private UserFetcherInterface $userFetcher,
    ) {}

    public function __invoke(Query $query): GetUserDto
    {
        return $this->userFetcher->getById($query->userId);
    }
}
