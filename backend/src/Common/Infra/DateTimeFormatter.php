<?php

declare(strict_types=1);

namespace Common\Infra;

use DateTimeImmutable;
use DateTimeZone;

final readonly class DateTimeFormatter
{
    public function __construct(
        private string $timezone,
    ) {
    }

    public function toMskTimezone(DateTimeImmutable $dateTime): DateTimeImmutable
    {
        return $dateTime->setTimezone(new DateTimeZone($this->timezone));
    }
}
