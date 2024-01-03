<?php

declare(strict_types=1);

namespace Auth\User\Subscriber;

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
        $payload['email'] = $payload['username'];
        $payload['id'] = $user->id;

        unset($payload['username']);

        $event->setData($payload);
    }
}
