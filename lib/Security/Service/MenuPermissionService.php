<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Service;

use KejawenLab\ApiSkeleton\Security\Model\GroupRepositoryInterface;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionableInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionInitiatorInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionRemoverInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class MenuPermissionService implements PermissionInitiatorInterface, PermissionRemoverInterface
{
    private string $class;

    public function __construct(private GroupRepositoryInterface $groupRepository, private PermissionRepositoryInterface $permissionRepository)
    {
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
