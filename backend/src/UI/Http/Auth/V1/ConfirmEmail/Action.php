<?php

declare(strict_types=1);

namespace UI\Http\Auth\V1\ConfirmEmail;

use Auth\User\Command\ConfirmEmail\Command;
use Common\Application\Bus\CommandBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response as ApiResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use UI\Http\Common\Response\ResponseDataWrapper;
use UI\Http\Common\Response\Violation;

#[OA\Tag(name: 'Регистрация')]
#[OA\Post(
    summary: 'Подтвержение email пользователя',
    requestBody: new OA\RequestBody(
        content: new OA\JsonContent(ref: new Model(type: Request::class))
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: 'Email пользователя успешно подтвержден',
            content: new OA\JsonContent(
                ref: new Model(type: ResponseDataWrapper::class),
                example: new ResponseDataWrapper(
                    data: new Response(
                        status: 'Email пользователя успешно подтвержден.'
                    )
                )
            )
        ),
        new OA\Response(
            response: 400,
            description: 'Некорректные данные запроса.',
            content: new Model(type: Violation::class),
        ),
        new OA\Response(
            response: 422,
            description: 'Ошибка бизнес-логики.',
            content: new Model(type: Violation::class),
        ),
    ]
)]
final class Action extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    #[Route(
        path: 'api/auth/v1/confirm-email',
        name: 'auth_confirm-email',
        methods: ['POST']
    )]
    public function __invoke(Request $request): ApiResponse
    {
        $this->commandBus->dispatch(
            new Command(
                userId: $request->userId,
                token: $request->token,
            )
        );

        return new JsonResponse(
            new ResponseDataWrapper(
                data: new Response('Email пользователя успешно подтвержден.')
            )
        );
    }
}
