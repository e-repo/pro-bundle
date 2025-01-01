<?php

declare(strict_types=1);

namespace Blog\Domain\Post\Entity;

enum ImageType: string
{
    case MAIN = 'main';

    case MAIN_THUMBNAIL_400 = 'main_thumbnail_400';

    case CONTENT = 'content';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
