<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class SemartApiSkeleton
{
    public const CODENAME = 'Apem Comal';

    public const VERSION = '5.3.3';

    public const VERSION_MAYOR = 50000;

    public const VERSION_MINOR = 3;

    public const VERSION_PATCH = 3;

    public static function getVersionNumber(): int
    {
        return static::VERSION_MAYOR + static::VERSION_MINOR + static::VERSION_PATCH;
    }
}
