<?php

declare(strict_types=1);

namespace App\Setting\Query;

use App\Pagination\AbstractQueryExtension as Base;
use App\Setting\Model\SettingInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
abstract class AbstractQueryExtension extends Base
{
    public function support(string $class): bool
    {
        return in_array(SettingInterface::class, class_implements($class));
    }
}
