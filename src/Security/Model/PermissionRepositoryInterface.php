<?php

declare(strict_types=1);

namespace App\Security\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface PermissionRepositoryInterface
{
    public function findPermission(GroupInterface $group, MenuInterface $menu): ?PermissionInterface;
}
