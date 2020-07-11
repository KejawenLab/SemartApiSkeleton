<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Service;

use Alpabit\ApiSkeleton\Security\Model\GroupRepositoryInterface;
use Alpabit\ApiSkeleton\Security\Model\MenuInterface;
use Alpabit\ApiSkeleton\Security\Model\PermissionableInterface;
use Alpabit\ApiSkeleton\Security\Model\PermissionInitiatorInterface;
use Alpabit\ApiSkeleton\Security\Model\PermissionRemoverInterface;
use Alpabit\ApiSkeleton\Security\Model\PermissionRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class MenuPermissionService implements PermissionInitiatorInterface, PermissionRemoverInterface
{
    private GroupRepositoryInterface $groupRepository;

    private PermissionRepositoryInterface $permissionRepository;

    private string $class;

    public function __construct(GroupRepositoryInterface $groupRepository, PermissionRepositoryInterface $permissionRepository)
    {
        $this->groupRepository = $groupRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function initiate(PermissionableInterface $object): void
    {
        /* @var MenuInterface $object */
        foreach ($this->groupRepository->findAll() as $group) {
            $permission = $this->permissionRepository->findPermission($group, $object);
            if (!$permission) {
                $permission = new $this->class();
            }

            $permission->setMenu($object);
            $permission->setGroup($group);

            $this->permissionRepository->persist($permission);
        }
    }

    public function remove(PermissionableInterface $object): void
    {
        /* @var MenuInterface $object */
        $this->permissionRepository->removeByMenu($object);
    }

    public function support(PermissionableInterface $object): bool
    {
        return $object instanceof MenuInterface;
    }

    public function setClass(string $class): void
    {
        $this->class = $class;
    }
}
