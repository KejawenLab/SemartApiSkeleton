<?php

declare(strict_types=1);

namespace App\Pagination\Model;

use Doctrine\ORM\QueryBuilder;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface FilterInterface
{
    public function apply(QueryBuilder $queryBuilder): void;

    public function support(string $class): bool;
}
