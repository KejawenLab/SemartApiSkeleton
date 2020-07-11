<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Setting\Query;

use Alpabit\ApiSkeleton\Pagination\Query\AbstractQueryExtension as Base;
use Alpabit\ApiSkeleton\Setting\Model\SettingInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
abstract class AbstractQueryExtension extends Base
{
    public function support(string $class): bool
    {
        return in_array(SettingInterface::class, class_implements($class));
    }
}
