<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Query\Group;

use KejawenLab\ApiSkeleton\Pagination\Query\AbstractQueryExtension as Base;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
abstract class AbstractQueryExtension extends Base
{
    public function support(string $class, Request $request): bool
    {
        return \in_array(GroupInterface::class, class_implements($class));
    }
}
