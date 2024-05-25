<?php

declare(strict_types=1);

namespace Auth\Domain\User\Fetcher;

use Auth\Domain\User\Dto\GetUserDto;
use Auth\Domain\User\Dto\UserDto;

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
