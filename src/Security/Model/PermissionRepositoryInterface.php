<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Security\Model;

use KejawenLab\Semart\ApiSkeleton\Pagination\Model\PaginatableRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface PermissionRepositoryInterface extends PaginatableRepositoryInterface
{
    public function findPermission(GroupInterface $group, MenuInterface $menu): ?PermissionInterface;

    public function removePermissionsByGroup(GroupInterface $group): void;

    public function removePermissionsByMenu(MenuInterface $menu): void;
}
