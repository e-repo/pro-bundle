<?php

namespace Auth\User\Domain\Fetcher;

use Auth\User\Domain\Dto\GetUserDto;
use Auth\User\Domain\Dto\UserDto;

interface UserFetcherInterface
{
    public function findByEmail(string $email): ?UserDto;
    public function findById(string $id): ?GetUserDto;
    public function getById(string $id): GetUserDto;
}
