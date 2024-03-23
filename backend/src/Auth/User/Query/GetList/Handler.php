<?php

declare(strict_types=1);

namespace Auth\User\Query\GetList;

use Auth\User\Domain\Dto\UsersByListFilterDto;
use Auth\User\Domain\Fetcher\ListFilter;
use Auth\User\Domain\Fetcher\UserFetcherInterface;
use CoreKit\Application\Bus\QueryHandlerInterface;

final readonly class Handler implements QueryHandlerInterface
{
    public function __construct(
        private UserFetcherInterface $userFetcher,
    ) {}

    /**
     * @param Query $query
     * @return UsersByListFilterDto
     */
    public function __invoke(Query $query): UsersByListFilterDto
    {
        $filter = new ListFilter(
            firstName: $query->firstName,
            lastName: $query->lastName,
            email: $query->email,
            role: $query->role,
            status: $query->status
        );

        $users = $this->userFetcher
            ->findByListFilter(
                filter: $filter,
                offset: $query->offset,
                limit: $query->limit
            );

        return new UsersByListFilterDto(
            userList: $users,
            total: $this->userFetcher->countByListFilter($filter)
        );
    }
}
