<?php

declare(strict_types=1);

namespace UI\Http\Auth\V1\User\ConfirmResetPassword;

use Auth\User\Command\ConfirmResetPassword\Command;
use CoreKit\Application\Bus\CommandBusInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use UI\Http\Common\Response\ResponseWrapper;
use UI\Http\Common\Response\Violation;

#[OA\Tag(name: 'Регистрация')]
#[OA\Post(
    summary: 'Сброс пароля',
    requestBody: new OA\RequestBody(
        content: new OA\JsonContent(ref: new Model(type: Request::class))
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: 'Запрос на сброс пароля успешно зарегистрирован',
            content: new OA\JsonContent(
                ref: new Model(type: ResponseWrapper::class),
                example: new ResponseWrapper(
                    data: new Response(
                        status: 'Пароль успешно обновлен.'
                    )
                )
            )
        ),
        new OA\Response(
            response: 400,
            description: 'Некорректные данные запроса',
            content: new Model(type: Violation::class),
        ),
        new OA\Response(
            response: 422,
            description: 'Ошибка бизнес-логики',
            content: new Model(type: Violation::class),
        ),
    ]
)]
final class Action extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {}

    #[Route(
        path: 'api/auth/v1/user/confirm-reset-password',
        name: 'auth_confirm-reset-password',
        methods: ['POST']
    )]
    public function __invoke(Request $request): JsonResponse
    {
        $this->commandBus->dispatch(
            new Command(
                token: $request->token,
                password: $request->password,
            )
        );

        return new JsonResponse(
            new ResponseWrapper(
                data: new Response('Пароль успешно обновлен.')
            )
        );
    }
}
