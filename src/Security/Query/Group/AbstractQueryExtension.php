<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Query\Group;

use Alpabit\ApiSkeleton\Pagination\Query\AbstractQueryExtension as Base;
use Alpabit\ApiSkeleton\Security\Model\GroupInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
abstract class AbstractQueryExtension extends Base
{
    public function support(string $class): bool
    {
        return in_array(GroupInterface::class, class_implements($class));
    }
}
