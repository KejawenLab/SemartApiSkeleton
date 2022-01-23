<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Model;

use KejawenLab\ApiSkeleton\Pagination\Model\PaginatableRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface PermissionRepositoryInterface extends PaginatableRepositoryInterface
{
    public function findPermission(GroupInterface $group, MenuInterface $menu, bool $cached = true): ?PermissionInterface;

    public function findPermissions(GroupInterface $group, array $menus): iterable;

    public function findAllowedMenusByGroup(GroupInterface $group, bool $parentOnly = false, bool $maxCache = false): iterable;

    public function findAllowedChildMenusByGroupAndMenu(GroupInterface $group, MenuInterface $menu, bool $maxCache = false): iterable;

    public function removeByGroup(GroupInterface $group): void;

    public function removeByMenu(MenuInterface $menu): void;
}
