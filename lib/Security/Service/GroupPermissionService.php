<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Service;

use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Security\Model\MenuRepositoryInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionableInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionInitiatorInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionRemoverInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class GroupPermissionService implements PermissionInitiatorInterface, PermissionRemoverInterface
{
    private string $class;

    public function __construct(
        private readonly MenuRepositoryInterface $menuRepository,
        private readonly PermissionRepositoryInterface $permissionRepository,
    ) {
    }

    /**
     * @param GroupInterface $object
     */
    public function initiate(PermissionableInterface $object): void
    {
        $permissionRepository = $this->permissionRepository;
        foreach ($this->menuRepository->findAll() as $key => $menu) {
            if (0 === $key % 7) {
                $permissionRepository->commit();
            }

            $permission = $permissionRepository->findPermission($object, $menu, false);
            if (null === $permission) {
                $permission = new $this->class();
            }

            $permission->setMenu($menu);
            $permission->setGroup($object);

            $permissionRepository->persist($permission);
        }
    }

    /**
     * @param GroupInterface $object
     */
    public function remove(PermissionableInterface $object): void
    {
        $this->permissionRepository->removeByGroup($object);
    }

    public function support(PermissionableInterface $object): bool
    {
        return $object instanceof GroupInterface;
    }

    public function setClass(string $class): void
    {
        $this->class = $class;
    }
}
