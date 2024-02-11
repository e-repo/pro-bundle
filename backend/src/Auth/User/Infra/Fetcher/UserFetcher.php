<?php

declare(strict_types=1);

namespace Auth\User\Infra\Fetcher;

use Auth\User\Domain\Dto\GetUserDto;
use Auth\User\Domain\Dto\UserDto;
use Auth\User\Domain\Fetcher\UserFetcherInterface;
use CoreKit\Infra\BaseFetcher;
use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use DomainException;

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
            ->fetchAssociative();

        return $user ? $this->makeUserDto($user) : null;
    }

    /**
     * @throws Exception
     */
    public function findById(string $id): ?GetUserDto
    {
        $qb = $this->createDBALQueryBuilder();

        $user = $qb
            ->select(
                'u.id as id',
                'u.name_first as first_name',
                'u.name_last as last_name',
                'u.email as email',
                'u.role as role',
                'u.status as status',
                'u.created_at as created_at',
            )
            ->from('auth.user', 'u')
            ->where(
                $qb->expr()->eq('u.id', ':userId')
            )
            ->setParameter('userId', $id)
            ->fetchAssociative();

        return $user ? $this->makeGetUserDto($user) : null;
    }

    /**
     * @throws Exception
     */
    public function getById(string $id): GetUserDto
    {
        $user = $this->findById($id);

        if (null === $user) {
            throw new DomainException(
                sprintf("Пользователь по идентификатору '%s' не найден", $id)
            );
        }

        return $user;
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
            createdAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:sT', $user['created_at'])
        );
    }

    private function makeGetUserDto(array $user): GetUserDto
    {
        return new GetUserDto(
            id: $user['id'],
            firstName: $user['first_name'],
            lastName: $user['last_name'],
            email: $user['email'],
            role: $user['role'],
            status: $user['status'],
            createdAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:sT', $user['created_at'])
        );
    }
}
