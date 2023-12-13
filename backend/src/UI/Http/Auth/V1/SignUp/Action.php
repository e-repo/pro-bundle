<?php

declare(strict_types=1);

namespace UI\Http\Auth\V1\SignUp;

use Auth\User\Command\SignUp\Command;
use Common\Application\Bus\CommandBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ApiResponse;
use Symfony\Component\Routing\Annotation\Route;
use UI\Http\Common\Response\ResponseDataWrapper;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use UI\Http\Common\Response\Violation;

#[OA\Tag(name: 'Регистрация')]
#[OA\Post(
    summary: 'Создание скидки партнера',
    requestBody: new OA\RequestBody(
        content: new OA\JsonContent(ref: new Model(type: Request::class))
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: 'Успешно - создано',
            content: new OA\JsonContent(
                ref: new Model(type: Response::class),
                example: new Response(
                    status: 'Пользователь создан успешно.'
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
        path: '/api/auth/v1/sign-up',
        name: 'auth_service_ping',
        methods: ['POST']
    )]
    public function __invoke(Request $request): ApiResponse
    {
        $command = new Command(
            firstName: $request->firstName,
            email: $request->email,
            password: $request->password,
        );

        $this->commandBus->dispatch($command);

        return new JsonResponse(
            new ResponseDataWrapper(
                data: new Response('Пользователь создан успешно.')
            )
        );
    }
}
