<?php

namespace Auth\User\Domain\Fetcher;

use Auth\User\Domain\Dto\GetUserDto;
use Auth\User\Domain\Dto\UserDto;

interface UserFetcherInterface
{
    public function findByEmail(string $email): ?UserDto;
    public function findById(string $id): ?GetUserDto;
    public function getById(string $id): GetUserDto;
    /**
     * @param ListFilter $filter
     * @param int $offset
     * @param int $limit
     * @return GetUserDto[]
     */
    public function findByListFilter(ListFilter $filter, int $offset, int $limit): array;

    public function countByListFilter(ListFilter $filter): int;
}
