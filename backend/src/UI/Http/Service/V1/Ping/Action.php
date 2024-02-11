<?php

declare(strict_types=1);

namespace UI\Http\Service\V1\Ping;

use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'Сервисные URL')]
final class Action extends AbstractController
{
    #[Route(
        path: '/api/service/v1/ping',
        name: 'auth_service_ping',
        methods: ['GET']
    )]
    public function __invoke(): Response
    {
        return new Response('Pong!');
    }
}
