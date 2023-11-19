<?php

namespace Auth\User\Domain\Entity;

use Auth\Infra\Repository\User\UserRepository;
use Auth\User\Domain\Entity\Event\UserCreatedEvent;
use Auth\User\Domain\Entity\Exception\EmailNotUniqueException;
use Auth\User\Domain\Entity\Specification\UniqueEmailSpecification;
use Auth\User\Domain\Service\PasswordHasher\Hasher;
use Auth\User\Domain\Service\PasswordHasher\PasswordHashedUserInterface;
use Common\Domain\Entity\EventRecordTrait;
use Common\Domain\Entity\HasEventsInterface;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`', schema: 'auth')]
class User implements PasswordHashedUserInterface, HasEventsInterface
{
    use EventRecordTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'user_id', options: ['comment' => 'Код пользователя'])]
    private IdVo $id;

    #[ORM\Embedded(NameVo::class)]
    private NameVo $name;

    #[ORM\Column(type: 'user_email', length: 100, unique: true)]
    private EmailVo $email;

    #[ORM\Column(length: 50, nullable: true, options: ['comment' => 'Токен для подтверждения email'])]
    private ?string $emailConfirmToken = null;

    #[ORM\Column(length: 50, enumType: Status::class, options: ['comment' => 'Статус пользователя'])]
    private Status $status;

    #[ORM\Column(length: 100, enumType: Role::class, options: ['comment' => 'Роль пользователя'])]
    private Role $role;

    #[ORM\Column(length: 255, options: ['comment' => 'Хэш пароля'])]
    private string $passwordHash;

    #[ORM\Column(length: 50, nullable: true, options: ['comment' => 'Токен сброса пароля'])]
    private ?string $resetPasswordToken = null;

    #[ORM\Column(
        type: Types::DATETIMETZ_IMMUTABLE,
        nullable: true,
        options: ['comment' => 'Дата действия токена сброса пароля']
    )]
    private ?DateTimeImmutable $passwordTokenExpires = null;

    #[ORM\Column(
        type: 'user_email',
        length: 100,
        nullable: true,
        options: ['comment' => 'Новый email (при смене)']
    )]
    private ?EmailVo $newEmail = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: ['comment' => 'Дата создания пользователя'])]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(
        type: Types::DATETIMETZ_IMMUTABLE,
        nullable: true,
        options: ['comment' => 'Дата обновления пользователя']
    )]
    private ?DateTimeImmutable $updatedAt = null;

    public function __construct(
        IdVo $id,
        NameVo $name,
        EmailVo $email,
        string $password,
        UniqueEmailSpecification $uniqueEmailSpecification,
        Hasher $hasher,
        ?string $emailConfirmToken = null,
        Status $status = Status::WAIT,
        Role $role = Role::USER,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->emailConfirmToken = $emailConfirmToken;
        $this->passwordHash = $password;
        $this->status = $status;
        $this->role = $role;

        if (! $uniqueEmailSpecification->isSatisfiedBy($this)) {
            throw new EmailNotUniqueException($email);
        }

        $hasher->hash($this);

        $this->record($this->makeUserCreatedEvent());
    }

    public function getId(): IdVo
    {
        return $this->id;
    }

    public function getName(): NameVo
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getPassword(): string
    {
        return  $this->passwordHash;
    }

    public function changePlainPassword(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    private function makeUserCreatedEvent(): UserCreatedEvent
    {
        return new UserCreatedEvent(
            firstname: $this->name->first,
            lastname: $this->name->last,
            email: $this->email->value,
            status: $this->status->value,
            role: $this->role->value,
            createdAt: $this->createdAt
        );
    }
}
