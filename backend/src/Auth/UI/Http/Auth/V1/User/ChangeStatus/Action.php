<?php

declare(strict_types=1);

namespace Auth\UI\Http\Auth\V1\User\ChangeStatus;

use Auth\Application\User\Command\ChangeStatus\Command;
use Auth\UI\Http\Auth\V1\User\ConfirmEmail\Response;
use CoreKit\Application\Bus\CommandBusInterface;
use CoreKit\UI\Http\Response\ResponseWrapper;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[OA\Tag(name: 'Регистрация')]
final class Action extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {}

    #[Route(
        path: 'api/auth/v1/user/{id}/status',
        name: 'auth_change-status',
        requirements: [
            'id' => Requirement::UUID,
        ],
        methods: ['PATCH']
    )]
    public function __invoke(Request $request): ResponseWrapper
    {
        $this->commandBus->dispatch(
            new Command(
                id: $request->id,
                status: $request->status
            )
        );

        return new ResponseWrapper(
            data: new Response('Статус пользователя успешно изменен.')
        );
    }
}
