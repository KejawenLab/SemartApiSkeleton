<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class SemartApiSkeleton
{
    final public const QUERY_CACHE_LIFETIME = 1027;

    final public const DISABLE_QUERY_CACHE_QUERY_STRING = 'dcq';

    final public const USER_DEVICE_ID = 'USER_DEVICE_ID';

    final public const API_CLIENT_DEVICE_ID = 'API_CLIENT_DEVICE_ID';

    final public const VERSION = '6.0.0';

    final public const VERSION_MAYOR = 60000;

    final public const VERSION_MINOR = 0;

    final public const VERSION_PATCH = 0;

    public static function getVersionNumber(): int
    {
        return self::VERSION_MAYOR + self::VERSION_MINOR + self::VERSION_PATCH;
    }
}
