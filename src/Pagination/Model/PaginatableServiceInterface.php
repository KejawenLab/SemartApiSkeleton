<?php

declare(strict_types=1);

namespace App\Pagination\Model;

use Doctrine\ORM\QueryBuilder;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface PaginatableServiceInterface
{
    public function getQueryBuilder(string $alias): QueryBuilder;
}
