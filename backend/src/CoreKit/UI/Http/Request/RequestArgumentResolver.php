<?php

declare(strict_types=1);

namespace CoreKit\UI\Http\Request;

use CoreKit\UI\Http\Exception\ViolationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use TypeError;

final readonly class RequestArgumentResolver implements ValueResolverInterface
{
    public function __construct(
        private BooleanAttributeDenormalizer $denormalizer,
        private ValidatorInterface $validator,
    ) {}

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $payload = [];
        $argumentType = $argument->getType();

        if (! $argumentType || ! is_subclass_of($argumentType, RequestPayloadInterface::class)) {
            return [];
        }

        if (false === empty($request->getContent())) {
            $payload = $request->toArray();
        }

        $payload = array_replace(
            $request->query->all(),
            $request->attributes->get('_route_params'),
            $payload
        );

        $dto = $this->payloadToDto($payload, $argumentType);
        $dto = $this->addRequestFilesToDto($dto, $request);

        $this->checkViolation($dto);

        yield $dto;
    }

    private function addRequestFilesToDto(object $dto, Request $request): object
    {
        if ('form' !== $request->getContentTypeFormat()) {
            return $dto;
        }

        foreach ($request->files->all() as $propertyName => $formData) {
            if (false === property_exists($dto, $propertyName)) {
                continue;
            }

            /**
             * При заголовке multipart form data можно передавать
             * вместе с файлом данные. Для этого в request выделяется
             * свойство с именем 'data'
             *
             * @ToDo Для определения типа формируемого объекта данных
             * идущих вместе с файлом возможно лучше использовать ArgumentMetadata,
             * либо потискать другой способ. ArgumentMetadata::getType()
             * ( $argumentType = $argument->getType() ) - не подойдет
             */
            //            if ($propertyName === 'data') {
            //                $dto->{$propertyName} = $this->payloadToDto(
            //                    payload: json_decode($formData, true, 512, JSON_THROW_ON_ERROR),
            //                    argumentType: $argumentType
            //                );
            //
            //                continue;
            //            }

            try {
                $dto->{$propertyName} = $formData;
            } catch (TypeError $exception) {
                $constraintViolation = new ConstraintViolation(
                    message: sprintf('Неверный тип данных у поля для передачи файлов \'%s\'', $propertyName),
                    messageTemplate: '',
                    parameters: [],
                    root: null,
                    propertyPath: null,
                    invalidValue: null
                );

                throw new ViolationException(
                    new ConstraintViolationList([$constraintViolation])
                );
            }
        }

        return $dto;
    }

    private function payloadToDto(array $payload, string $argumentType): mixed
    {
        return $this->denormalizer
            ->denormalize(
                data: $payload,
                type: $argumentType,
                context: [
                    'disable_type_enforcement' => true,
                ]
            );
    }

    private function checkViolation(mixed $dto): void
    {
        $violations = $this->validator->validate($dto);

        if ($violations->count() > 0) {
            throw new ViolationException($violations);
        }
    }
}
