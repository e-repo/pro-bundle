<?php

declare(strict_types=1);

namespace UI\Http\Auth\V1\User\GetUser;

use Auth\User\Domain\Dto\GetUserDto;
use Auth\User\Query\User\GetUser\Query;
use Common\Application\Bus\QueryBusInterface;
use Common\Infra\DateTimeFormatter;
use Common\Infra\OpenApiDateTime;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use UI\Http\Common\Exception\ViolationException;
use UI\Http\Common\Response\ResponseWrapper;
use UI\Http\Common\Response\Violation;

#[OA\Tag(name: 'Регистрация')]
#[OA\Get(
    summary: 'Получения пользователя',
    parameters: [
        new OA\Parameter(
            name: 'id',
            description: 'Идентификатор пользователя',
            in: 'path'
        ),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Пользователь успешно создан',
            content: new OA\JsonContent(
                ref: new Model(type: ResponseWrapper::class),
                example: new ResponseWrapper(
                    data: new Response(
                        id: '789739fd-6c6f-4cb7-8eb8-3a548a1239b3',
                        firstName: 'Алексей',
                        lastName: 'Иванов',
                        email: 'test@test.ru',
                        role: 'ROLE_USER',
                        status: 'active',
                        createdAt: new OpenApiDateTime()
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
        private readonly ValidatorInterface $validator,
        private readonly QueryBusInterface $queryBus,
        private readonly DateTimeFormatter $dateTimeFormatter,
    ) {
    }

    #[Route(
        path: 'api/auth/v1/user/{id}',
        name: 'auth_get-user',
        requirements: ['id' => '^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$'], // UUID
        methods: ['GET']
    )]
    public function __invoke(string $id): ResponseWrapper
    {
        $violationList = $this->validator->validate(
            new Request($id)
        );

        if ($violationList->count() > 0) {
            throw new ViolationException($violationList);
        }

        /** @var GetUserDto $result */
        $result = $this->queryBus->dispatch(
            new Query($id)
        );

        return new ResponseWrapper($this->makeResponse($result));
    }

    private function makeResponse(GetUserDto $result): Response
    {
        return new Response(
            id: $result->id,
            firstName: $result->firstName,
            lastName: $result->lastName,
            email: $result->email,
            role: $result->role,
            status: $result->status,
            createdAt: $this->dateTimeFormatter->toMskTimezone($result->createdAt)
        );
    }
}
