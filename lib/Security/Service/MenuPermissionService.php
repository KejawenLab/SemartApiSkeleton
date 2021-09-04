<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Service;

use KejawenLab\ApiSkeleton\Security\Model\GroupRepositoryInterface;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionableInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionInitiatorInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionRemoverInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionRepositoryInterface;
use Swoole\Coroutine;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class MenuPermissionService implements PermissionInitiatorInterface, PermissionRemoverInterface
{
    private string $class;

    public function __construct(private GroupRepositoryInterface $groupRepository, private PermissionRepositoryInterface $permissionRepository)
    {
    }

    /**
     * @param MenuInterface $object
     */
    public function initiate(PermissionableInterface $object): void
    {
        $permissionRepository = $this->permissionRepository;
        foreach ($this->groupRepository->findAll() as $key =>  $group) {
            Coroutine::create(function () use ($permissionRepository, $object, $key, $group): void {
                if (0 === $key % 7) {
                    $permissionRepository->commit();
                }

                $permission = $permissionRepository->findPermission($group, $object);
                if ($permission === null) {
                    $permission = new $this->class();
                }

                $permission->setMenu($object);
                $permission->setGroup($group);

                $permissionRepository->persist($permission);
            });
        }
    }

    /**
     * @param MenuInterface $object
     */
    public function remove(PermissionableInterface $object): void
    {
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
