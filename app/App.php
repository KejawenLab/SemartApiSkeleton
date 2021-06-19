<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Application;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class App
{
    public const VERSION_MAYOR = 10000;

    public const VERSION_MINOR = 0;

    public const VERSION_PATCH = 0;

    public static function getVersionNumber(): int
    {
        return self::VERSION_MAYOR + self::VERSION_MINOR + self::VERSION_PATCH;
    }
}
