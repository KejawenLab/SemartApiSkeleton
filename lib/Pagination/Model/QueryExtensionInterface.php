<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Pagination\Model;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
interface QueryExtensionInterface
{
    public function apply(QueryBuilder $queryBuilder, Request $request): void;

    public function support(string $class, Request $request): bool;
}
