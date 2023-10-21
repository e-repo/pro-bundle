<?php

declare(strict_types=1);

namespace UI\Http\Auth\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Сервисные URL')]
final class ServiceAction extends AbstractController
{
    #[Route(
        path: '/api/v1/auth/service/ping',
        name: 'auth_service_ping',
        methods: ['GET']
    )]
    public function __invoke(): Response
    {
        return new Response('Pong!');
    }
}
