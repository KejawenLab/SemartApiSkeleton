<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Security\Query\Menu;

use KejawenLab\Semart\ApiSkeleton\Pagination\AbstractQueryExtension as Base;
use KejawenLab\Semart\ApiSkeleton\Security\Model\MenuInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
abstract class AbstractQueryExtension extends Base
{
    public function support(string $class): bool
    {
        return in_array(MenuInterface::class, class_implements($class));
    }
}
