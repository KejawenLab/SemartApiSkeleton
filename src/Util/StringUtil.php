<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Util;

use Symfony\Component\String\UnicodeString;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class StringUtil
{
    public static function uppercase(string $string): string
    {
        return (new UnicodeString($string))->upper()->toString();
    }

    public static function lowercase(string $string): string
    {
        return (new UnicodeString($string))->lower()->toString();
    }

    public static function title(string $string, bool $allWords = true): string
    {
        return (new UnicodeString($string))->lower()->title($allWords)->toString();
    }
}
