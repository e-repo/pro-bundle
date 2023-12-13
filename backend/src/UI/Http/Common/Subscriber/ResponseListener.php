<?php

declare(strict_types=1);

namespace UI\Http\Common\Subscriber;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use UI\Http\Common\Response\ResponseFactory;

final readonly class ResponseListener
{
    public function __construct(
        private ResponseFactory $responseFactory,
    ) {
    }

    public function __invoke(ViewEvent $event): void
    {
        $value = $event->getControllerResult();

        if ($value instanceof Response) {
            return;
        }

        $event->setResponse(
            $this->responseFactory->toJsonResponse(
                $value
            )
        );
    }
}
