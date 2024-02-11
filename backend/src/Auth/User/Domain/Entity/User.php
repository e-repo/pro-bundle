<?php

namespace Auth\User\Domain\Entity;

use Auth\User\Domain\Entity\Event\UserCreatedEvent;
use Auth\User\Domain\Entity\Event\UserPasswordResetEvent;
use Auth\User\Domain\Entity\Exception\EmailNotUniqueException;
use Auth\User\Domain\Entity\Specification\UniqueEmailSpecification;
use Auth\User\Domain\Service\PasswordHasher\Hasher;
use Auth\User\Domain\Service\PasswordHasher\PasswordHashedUserInterface;
use CoreKit\Domain\Entity\EventRecordTrait;
use CoreKit\Domain\Entity\HasEventsInterface;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DomainException;
use Ramsey\Uuid\Uuid;

#[ORM\Entity]
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

    #[ORM\Column(
        length: 100,
        nullable: true,
        enumType: RegistrationSource::class,
        options: ['comment' => 'Система-источник регистрации пользователя'])
    ]
    private RegistrationSource $registrationSource;

    #[ORM\Embedded(class: ResetPasswordToken::class, columnPrefix: false)]
    private ResetPasswordToken $resetPasswordToken;

    #[ORM\Column(
        type: 'user_email',
        length: 100,
        nullable: true,
        options: ['comment' => 'Новый email (при смене)']
    )]
    private ?EmailVo $newEmail = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, options: ['comment' => 'Дата создания пользователя'])]
    private DateTimeImmutable $createdAt;

    public function __construct(
        IdVo $id,
        NameVo $name,
        EmailVo $email,
        string $password,
        string $registrationSource,
        UniqueEmailSpecification $uniqueEmailSpecification,
        Hasher $hasher,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->passwordHash = $password;
        $this->registrationSource = RegistrationSource::tryFrom($registrationSource);
        $this->status = Status::WAIT;
        $this->role = Role::USER;
        $this->resetPasswordToken = new ResetPasswordToken();
        $this->emailConfirmToken = Uuid::uuid4()->toString();
        $this->createdAt = new DateTimeImmutable();

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

    public function getRegistrationSource(): RegistrationSource
    {
        return $this->registrationSource;
    }

    public function getEmailConfirmToken(): string
    {
        return $this->emailConfirmToken;
    }

    public function changePlainPassword(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    public function confirmUserEmail(string $emailConfirmToken): void
    {
        if ($emailConfirmToken !== $this->emailConfirmToken) {
            throw new DomainException('Передан не верный токен для подтверждения email.');
        }

        $this->status = Status::ACTIVE;
        $this->emailConfirmToken = null;
    }

    public function block(): void
    {
        if ($this->status === Status::BLOCKED) {
            throw new DomainException('Пользователь уже заблокирован');
        }

        $this->status = Status::ACTIVE;
        $this->emailConfirmToken = null;
    }

    public function resetPassword(string $registrationSource): void
    {
        if (! $this->isActive()) {
            throw new DomainException('Невозможно сбросить пароль т.к пользователь не является активным');
        }

        $currentDate = new DateTimeImmutable();

        if (! $this->resetPasswordToken->isExpired($currentDate)) {
            throw new DomainException('Запрос на сброс пароля уже был отправлен. Действует в течении суток');
        }

        $this->resetPasswordToken = new ResetPasswordToken(
            resetPasswordToken: Uuid::uuid4()->toString(),
            passwordTokenExpires: $currentDate->modify('+1 day')
        );

        $this->record(
            new UserPasswordResetEvent(
                email: $this->email,
                resetPasswordToken: $this->resetPasswordToken->getToken(),
                passwordTokenExpires: $this->resetPasswordToken->getPasswordTokenExpires(),
                registrationSource: $registrationSource
            )
        );
    }

    private function makeUserCreatedEvent(): UserCreatedEvent
    {
        return new UserCreatedEvent(
            id: $this->id->value,
            firstname: $this->name->first,
            lastname: $this->name->last,
            email: $this->email->value,
            emailConfirmToken: $this->emailConfirmToken,
            status: $this->status->value,
            role: $this->role->value,
            registrationSource: $this->registrationSource->value,
            createdAt: $this->createdAt
        );
    }

    private function isActive(): bool
    {
        return Status::ACTIVE === $this->status;
    }
}
