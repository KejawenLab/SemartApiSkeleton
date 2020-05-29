<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Pagination\Model;

use KejawenLab\Semart\ApiSkeleton\Service\Model\ServiceableRepositoryInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface PaginatableRepositoryInterface extends ServiceableRepositoryInterface
{
    public function queryBuilder(string $alias): QueryBuilder;
}
