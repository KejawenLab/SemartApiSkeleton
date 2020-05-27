<?php

declare(strict_types=1);

namespace App\Pagination;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class AliasHelper
{
    public static function getAlias(array $exclude = []): string
    {
        $list = 'abcdefghijklmnopqrstuvwxyz';
        $alias = $list[rand(0, \strlen($list) - 1)];
        if (\in_array($alias, $exclude)) {
            return static::getAlias($exclude);
        }

        return $alias;
    }
}
