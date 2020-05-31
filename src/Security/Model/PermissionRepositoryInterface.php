<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Model;

use Alpabit\ApiSkeleton\Pagination\Model\PaginatableRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface PermissionRepositoryInterface extends PaginatableRepositoryInterface
{
    public function findPermission(GroupInterface $group, MenuInterface $menu): ?PermissionInterface;

    public function findAllowedMenusByGroup(GroupInterface $user): array;

    public function removeByGroup(GroupInterface $group): void;

    public function removeByMenu(MenuInterface $menu): void;
}
