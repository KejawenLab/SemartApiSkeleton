<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Model;

use Alpabit\ApiSkeleton\Pagination\Model\PaginatableRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
interface MenuRepositoryInterface extends PaginatableRepositoryInterface
{
    public function findByCode(string $code): ?MenuInterface;

    public function findChilds(MenuInterface $menu): iterable;
}
