<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class SemartApiSkeleton
{
    final public const CODENAME = 'Dodol Duren';

    final public const VERSION = '5.7.4';

    final public const VERSION_MAYOR = 50000;

    final public const VERSION_MINOR = 700;

    final public const VERSION_PATCH = 4;

    public static function getVersionNumber(): int
    {
        return self::VERSION_MAYOR + self::VERSION_MINOR + self::VERSION_PATCH;
    }
}
