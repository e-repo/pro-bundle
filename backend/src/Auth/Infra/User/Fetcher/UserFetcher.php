<?php

declare(strict_types=1);

namespace Auth\Infra\User\Fetcher;

use Auth\User\Domain\Dto\UserDto;
use Auth\User\Domain\Fetcher\UserFetcherInterface;
use Common\Infra\BaseFetcher;
use Doctrine\DBAL\Exception;

final readonly class UserFetcher extends BaseFetcher implements UserFetcherInterface
{
    /**
     * @throws Exception
     */
    public function findByEmail(string $email): ?UserDto
    {
        $qb = $this->createDBALQueryBuilder();

        $user = $qb
            ->select(
                'u.id as id',
                'u.name_first as first_name',
                'u.name_last as last_name',
                'u.email as email',
                'u.password_hash as password_hash',
                'u.role as role',
                'u.status as status',
                'u.created_at as created_at',
            )
            ->from('auth.user', 'u')
            ->where(
                $qb->expr()->like('u.email', ':email')
            )
            ->setParameter('email', $email)
            ->fetchOne();

        return $user ? $this->makeUserDto($user) : null;
    }

    private function makeUserDto(array $user): UserDto
    {
        return new UserDto(
            id: $user['id'],
            firstName: $user['first_name'],
            lastName: $user['last_name'],
            email: $user['email'],
            passwordHash: $user['password_hash'],
            role: $user['role'],
            status: $user['status'],
            createdAt: $user['created_at']
        );
    }
}
