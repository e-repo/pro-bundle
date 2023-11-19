<?php

declare(strict_types=1);

namespace Auth\User\Domain\Entity\Type;

use Auth\User\Domain\Entity\EmailVo;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class EmailType extends StringType
{
    public const NAME = 'user_email';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof EmailVo ? $value->value : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?EmailVo
    {
        return null !== $value ? new EmailVo($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform) : bool
    {
        return true;
    }
}
