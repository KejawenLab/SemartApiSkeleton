<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class SemartApiSkeleton
{
    public const CODENAME = 'Apem Comal';

    public const VERSION = '5.0.0';

    public const VERSION_MAYOR = 050000;

    public const VERSION_MINOR = 0000;

    public const VERSION_PATCH = 00;

    public static function getVersionNumber(): int
    {
        return static::VERSION_MAYOR + static::VERSION_MINOR + static::VERSION_PATCH;
    }
}
