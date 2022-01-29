<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class SemartApiSkeleton
{
    final public const STATIC_CACHE_LIFETIME = 1027;

    final public const STATIC_CACHE_PERIOD = 'P17M';

    final public const STATIC_CACHE_HEADER = 'X-Semart-Content-ID';

    final public const DISABLE_CACHE_QUERY_STRING = 'dc';

    final public const CODENAME = 'Dodol Duren';

    final public const VERSION = '5.8.7';

    final public const VERSION_MAYOR = 50000;

    final public const VERSION_MINOR = 800;

    final public const VERSION_PATCH = 7;

    public static function getVersionNumber(): int
    {
        return self::VERSION_MAYOR + self::VERSION_MINOR + self::VERSION_PATCH;
    }
}
