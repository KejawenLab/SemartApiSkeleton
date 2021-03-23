<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Application;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class App
{
    public const VERSION_MAYOR = 000000;

    public const VERSION_MINOR = 0000;

    public const VERSION_PATCH = 00;

    public static function getVersionNumber(): int
    {
        return static::VERSION_MAYOR + static::VERSION_MINOR + static::VERSION_PATCH;
    }
}
