<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Setting\Query;

use KejawenLab\Semart\ApiSkeleton\Pagination\AbstractQueryExtension as Base;
use KejawenLab\Semart\ApiSkeleton\Setting\Model\SettingInterface;

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
