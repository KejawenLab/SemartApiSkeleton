<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Pagination\Model;

use Doctrine\ORM\QueryBuilder;
use Alpabit\ApiSkeleton\Service\Model\ServiceableRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface PaginatableRepositoryInterface extends ServiceableRepositoryInterface
{
    public function queryBuilder(string $alias): QueryBuilder;
}
