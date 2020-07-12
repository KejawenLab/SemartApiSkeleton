<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Setting\Query;

use KejawenLab\ApiSkeleton\Pagination\Query\AbstractQueryExtension as Base;
use KejawenLab\ApiSkeleton\Setting\Model\SettingInterface;

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
