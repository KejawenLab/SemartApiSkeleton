<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Security\Model;

use KejawenLab\Semart\ApiSkeleton\Pagination\Model\PaginatableRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface MenuRepositoryInterface extends PaginatableRepositoryInterface
{
    public function findByCode(string $code): ?MenuInterface;
}
