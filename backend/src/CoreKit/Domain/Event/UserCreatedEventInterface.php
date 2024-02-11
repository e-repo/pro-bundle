<?php

namespace CoreKit\Domain\Event;

use DateTimeImmutable;

interface UserCreatedEventInterface
{
    public function getId(): string;
    public function getFirstname(): string;
    public function getLastname(): ?string;
    public function getEmail(): string;
    public function getEmailConfirmToken(): ?string;
    public function getStatus(): string;
    public function getRole(): string;
    public function getRegistrationSource(): string;
    public function getCreatedAt(): DateTimeImmutable;
}
