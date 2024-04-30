<?php

declare(strict_types=1);

namespace Auth\User\Listener;

use Auth\User\Infra\Service\Security\UserIdentity;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

final class JWTCreatedListener
{
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        /** @var UserIdentity $user */
        $user = $event->getUser();
        $payload = $event->getData();

        $payload['firstName'] = $user->firstName;
        $payload['email'] = $user->email;
        $payload['id'] = $user->id;

        $event->setData($payload);
    }
}
