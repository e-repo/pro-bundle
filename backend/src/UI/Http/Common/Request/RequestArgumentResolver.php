<?php

declare(strict_types=1);

namespace UI\Http\Common\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use UI\Http\Common\Exception\ViolationException;

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

        $this->checkViolation($dto);

        yield $dto;
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
