<?php

declare(strict_types=1);

namespace App\Security\Query;

use App\Pagination\AbstractQueryExtension as Base;
use App\Security\Model\GroupInterface;

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
