<?php

declare(strict_types=1);

namespace Auth\User\Domain\Entity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use DateTimeImmutable;

#[ORM\Embeddable]
final class ResetPasswordToken
{
    #[ORM\Column(length: 50, nullable: true, options: ['comment' => 'Токен сброса пароля'])]
    private ?string $resetPasswordToken;

    #[ORM\Column(
        type: Types::DATETIMETZ_IMMUTABLE,
        nullable: true,
        options: ['comment' => 'Дата действия токена сброса пароля']
    )]
    private ?DateTimeImmutable $passwordTokenExpires;

    public function __construct(
        ?string $resetPasswordToken = null,
        ?DateTimeImmutable $passwordTokenExpires = null,
    ) {
        $this->resetPasswordToken = $resetPasswordToken;
        $this->passwordTokenExpires = $passwordTokenExpires;
    }

    public function isExpired(\DateTimeImmutable $date): bool
    {
        return $this->passwordTokenExpires <= $date;
    }

    public function getToken(): ?string
    {
        return $this->resetPasswordToken;
    }

    public function getPasswordTokenExpires(): ?\DateTimeImmutable
    {
        return $this->passwordTokenExpires;
    }
}
