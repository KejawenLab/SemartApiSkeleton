<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Query\User;

use Alpabit\ApiSkeleton\Pagination\Query\AbstractQueryExtension as Base;
use Alpabit\ApiSkeleton\Security\Model\UserInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
abstract class AbstractQueryExtension extends Base
{
    public function support(string $class): bool
    {
        return in_array(UserInterface::class, class_implements($class));
    }
}
