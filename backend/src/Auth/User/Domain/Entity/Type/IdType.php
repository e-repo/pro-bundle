<?php

declare(strict_types=1);

namespace Auth\User\Domain\Entity\Type;

use Auth\User\Domain\Entity\IdVo;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

final class IdType extends GuidType
{
    public const NAME = 'user_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof IdVo ? $value->value : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?IdVo
    {
        return null !== $value ? new IdVo($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
