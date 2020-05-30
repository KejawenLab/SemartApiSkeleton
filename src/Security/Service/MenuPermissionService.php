<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Security\Service;

use KejawenLab\Semart\ApiSkeleton\Security\Model\GroupRepositoryInterface;
use KejawenLab\Semart\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\Semart\ApiSkeleton\Security\Model\PermissionableInterface;
use KejawenLab\Semart\ApiSkeleton\Security\Model\PermissionInitiatorInterface;
use KejawenLab\Semart\ApiSkeleton\Security\Model\PermissionRemoverInterface;
use KejawenLab\Semart\ApiSkeleton\Security\Model\PermissionRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class MenuPermissionService implements PermissionInitiatorInterface, PermissionRemoverInterface
{
    private $groupRepository;

    private $permissionRepository;

    private $class;

    public function __construct(GroupRepositoryInterface $groupRepository, PermissionRepositoryInterface $permissionRepository)
    {
        $this->groupRepository = $groupRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function initiate(PermissionableInterface $object): void
    {
        /** @var MenuInterface $object */
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
        /** @var MenuInterface $object */
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
