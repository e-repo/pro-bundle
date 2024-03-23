<?php

declare(strict_types=1);

namespace UI\Http\Common\Response;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use UI\Http\Common\Response\Violation\ViolationItem;

final readonly class Violation
{
    public function __construct(
        public string $message,
        /** @var ViolationItem[] $errors */
        #[OA\Property(ref: new Model(type: ViolationItem::class))]
        public array $errors = [],
    ) {}
}
