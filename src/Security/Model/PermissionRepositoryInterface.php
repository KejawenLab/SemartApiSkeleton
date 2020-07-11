<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Model;

use Alpabit\ApiSkeleton\Pagination\Model\PaginatableRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
interface PermissionRepositoryInterface extends PaginatableRepositoryInterface
{
    public function findPermission(GroupInterface $group, MenuInterface $menu): ?PermissionInterface;

    public function findPermissions(GroupInterface $group, array $menus): iterable;

    public function findAllowedMenusByGroup(GroupInterface $user): iterable;

    public function removeByGroup(GroupInterface $group): void;

    public function removeByMenu(MenuInterface $menu): void;
}
