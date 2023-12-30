<?php

namespace Auth\User\Domain\Fetcher;

use Auth\User\Domain\Dto\UserDto;

interface UserFetcherInterface
{
    public function findByEmail(string $email): ?UserDto;
}