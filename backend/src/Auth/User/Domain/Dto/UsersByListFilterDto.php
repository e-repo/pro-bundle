<?php

declare(strict_types=1);

namespace Auth\User\Domain\Dto;

final readonly class UsersByListFilterDto
{
    /**
     * @param GetUserDto[] $userList
     */
    public function __construct(
        public array $userList,
        public int $total
    ) {}
}
