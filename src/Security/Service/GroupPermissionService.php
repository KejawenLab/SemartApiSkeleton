<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Service;

use Alpabit\ApiSkeleton\Security\Model\GroupInterface;
use Alpabit\ApiSkeleton\Security\Model\MenuRepositoryInterface;
use Alpabit\ApiSkeleton\Security\Model\PermissionableInterface;
use Alpabit\ApiSkeleton\Security\Model\PermissionInitiatorInterface;
use Alpabit\ApiSkeleton\Security\Model\PermissionRemoverInterface;
use Alpabit\ApiSkeleton\Security\Model\PermissionRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class GroupPermissionService implements PermissionInitiatorInterface, PermissionRemoverInterface
{
    private MenuRepositoryInterface $menuRepository;

    private PermissionRepositoryInterface $permissionRepository;

    private string $class;

    public function __construct(MenuRepositoryInterface $menuRepository, PermissionRepositoryInterface $permissionRepository)
    {
        $this->menuRepository = $menuRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function initiate(PermissionableInterface $object): void
    {
        /** @var GroupInterface $object */
        foreach ($this->getAllMenu() as $menu) {
            $permission = $this->permissionRepository->findPermission($object, $menu);
            if (!$permission) {
                $permission = new $this->class();
            }

            $permission->setMenu($menu);
            $permission->setGroup($object);

            $this->permissionRepository->persist($permission);
        }
    }

    public function remove(PermissionableInterface $object): void
    {
        /** @var GroupInterface $object */
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

    private function getAllMenu(): iterable
    {
        $menus = $this->menuRepository->findAll();
        foreach ($menus as $menu) {
            yield $menu;
        }
    }
}
