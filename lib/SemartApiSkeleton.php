<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class SemartApiSkeleton
{
    public const CODENAME = 'Dodol Duren';

    public const VERSION = '5.7.2';

    public const VERSION_MAYOR = 50000;

    public const VERSION_MINOR = 700;

    public const VERSION_PATCH = 2;

    public static function getVersionNumber(): int
    {
        return self::VERSION_MAYOR + self::VERSION_MINOR + self::VERSION_PATCH;
    }
}
