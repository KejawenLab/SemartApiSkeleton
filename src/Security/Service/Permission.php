<?php

declare(strict_types=1);

namespace App\Security\Service;

use App\Security\Model\GroupInterface;
use App\Security\Model\MenuInterface;
use App\Security\Model\PermissionInterface;
use App\Security\Model\PermissionRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
class Permission
{
    private $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function getPermission(GroupInterface $group, MenuInterface $menu): ?PermissionInterface
    {
        return $this->permissionRepository->findPermission($group, $menu);
    }
}
