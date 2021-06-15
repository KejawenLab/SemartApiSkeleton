<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Pagination\Model;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\ApiSkeleton\Service\Model\ServiceableRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
interface PaginatableRepositoryInterface extends ServiceableRepositoryInterface
{
    public function queryBuilder(string $alias): QueryBuilder;
}
