<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class SemartApiSkeleton
{
    public final const CODENAME = 'Dodol Duren';

    public final const VERSION = '5.7.4';

    public final const VERSION_MAYOR = 50000;

    public final const VERSION_MINOR = 700;

    public final const VERSION_PATCH = 4;

    public static function getVersionNumber(): int
    {
        return self::VERSION_MAYOR + self::VERSION_MINOR + self::VERSION_PATCH;
    }
}
