<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Model;

use KejawenLab\ApiSkeleton\Pagination\Model\PaginatableRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface MenuRepositoryInterface extends PaginatableRepositoryInterface
{
    public function findByCode(string $code): ?MenuInterface;

    public function findChilds(MenuInterface $menu): iterable;
}
