<?php

declare(strict_types=1);

namespace App\Security\Model;

use App\Pagination\Model\PaginatableRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface MenuRepositoryInterface extends PaginatableRepositoryInterface
{
    public function findByCode(string $code): ?MenuInterface;
}
