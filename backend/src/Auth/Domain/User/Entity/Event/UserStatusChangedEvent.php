<?php

declare(strict_types=1);

namespace Auth\Domain\User\Entity\Event;

use CoreKit\Domain\Event\EventInterface;
use CoreKit\Domain\Event\UserStatusChangedEventInterface;

final readonly class UserStatusChangedEvent implements EventInterface, UserStatusChangedEventInterface
{
    public function __construct(
        private string $id,
        private string $firstname,
        private string $email,
        private string $status,
        private string $role,
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getRole(): string
    {
        return $this->role;
    }
}
