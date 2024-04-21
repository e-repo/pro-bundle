<?php

declare(strict_types=1);

namespace Auth\User\Infra\Repository;

use Auth\User\Domain\Entity\EmailVo;
use Auth\User\Domain\Entity\User;
use Auth\User\Domain\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $user): void
    {
        $this->_em->persist($user);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->findOneBy([
            'email' => new EmailVo($email),
        ]);
    }

    public function findByResetPasswordToken(string $token): ?User
    {
        return $this->findOneBy([
            'resetPasswordToken.token' => $token,
        ]);
    }
}
