<?php

declare(strict_types=1);

namespace App\Util;

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
}
