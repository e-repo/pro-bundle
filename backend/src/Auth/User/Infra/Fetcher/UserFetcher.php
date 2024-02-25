<?php

declare(strict_types=1);

namespace Auth\User\Infra\Fetcher;

use Auth\User\Domain\Dto\GetUserDto;
use Auth\User\Domain\Dto\UserDto;
use Auth\User\Domain\Fetcher\ListFilter;
use Auth\User\Domain\Fetcher\UserFetcherInterface;
use CoreKit\Infra\BaseFetcher;
use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
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
                'u.name_first as name_first',
                'u.name_last as name_last',
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
                'u.name_first as name_first',
                'u.name_last as name_last',
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
    public function findByListFilter(ListFilter $filter, int $offset, int $limit): array
    {
        $userList = $this->makeQBByListFilter($filter)
            ->select(
                'u.id as id',
                'u.name_first as name_first',
                'u.name_last as name_last',
                'u.email as email',
                'u.role as role',
                'u.status as status',
                'u.created_at as created_at',
            )
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->fetchAllAssociative();

        return array_map($this->makeGetUserDto(...), $userList);
    }

    /**
     * @throws Exception
     */
    public function countByListFilter(ListFilter $filter): int
    {
        $count = $this->makeQBByListFilter($filter)
            ->select('count(*)')
            ->fetchOne();

        return $count ? (int)$count : 0;
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
            firstName: $user['name_first'],
            lastName: $user['name_last'],
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
            firstName: $user['name_first'],
            lastName: $user['name_last'],
            email: $user['email'],
            role: $user['role'],
            status: $user['status'],
            createdAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:sT', $user['created_at'])
        );
    }

    private function makeQBByListFilter(ListFilter $filter): QueryBuilder
    {
        $qb = $this->createDBALQueryBuilder()
            ->from('auth.user', 'u');

        if (null !== $filter->firstName) {
            $firstNameValue = addcslashes(mb_strtolower($filter->firstName), '%_');

            $qb
                ->andWhere(
                    $qb->expr()->like('LOWER(u.name_first)', ':firstName')
                )
                ->setParameter('firstName', $firstNameValue . '%');
        }

        if (null !== $filter->lastName) {
            $lastNameValue = addcslashes(mb_strtolower($filter->lastName), '%_');

            $qb
                ->andWhere(
                    $qb->expr()->like('LOWER(u.name_last)', ':lastName')
                )
                ->setParameter('lastName', $lastNameValue . '%');
        }

        if (null !== $filter->email) {
            $qb
                ->andWhere(
                    $qb->expr()->like('u.email', ':email')
                )
                ->setParameter('email', $filter->email);
        }

        if (null !== $filter->role) {
            $qb
                ->andWhere(
                    $qb->expr()->like('u.role', ':role')
                )
                ->setParameter('role', $filter->role);
        }

        if (null !== $filter->status) {
            $qb
                ->andWhere(
                    $qb->expr()->like('u.status', ':status')
                )
                ->setParameter('status', $filter->status);
        }

        return $qb;
    }
}
