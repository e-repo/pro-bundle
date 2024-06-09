<?php

declare(strict_types=1);

namespace CoreKit\Domain\Event;

interface UserStatusChangedEventInterface
{
    public function getId(): string;

    public function getFirstname(): string;

    public function getEmail(): string;

    public function getStatus(): string;

    public function getRole(): string;
}
