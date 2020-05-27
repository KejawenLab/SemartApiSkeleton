<?php

declare(strict_types=1);

namespace App\Pagination\Model;

use App\Pagination\Pagination;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface PaginatableRepositoryInterface
{
    public function paginate(Pagination $pagination, array $filters): array;

    public function count(array $filters);
}
