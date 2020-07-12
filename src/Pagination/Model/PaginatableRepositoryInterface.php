<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Pagination\Model;

use KejawenLab\ApiSkeleton\Service\Model\ServiceableRepositoryInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
interface PaginatableRepositoryInterface extends ServiceableRepositoryInterface
{
    public function queryBuilder(string $alias): QueryBuilder;
}
