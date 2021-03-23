<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Query\Menu;

use KejawenLab\ApiSkeleton\Pagination\Query\AbstractQueryExtension as Base;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
abstract class AbstractQueryExtension extends Base
{
    public function support(string $class, Request $request): bool
    {
        return in_array(MenuInterface::class, class_implements($class));
    }
}
