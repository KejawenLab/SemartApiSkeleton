<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Generator;

use Doctrine\ORM\EntityManagerInterface;
use Alpabit\ApiSkeleton\Security\Model\MenuInterface;
use Alpabit\ApiSkeleton\Security\Service\GroupService;
use Alpabit\ApiSkeleton\Security\Service\MenuService;
use Alpabit\ApiSkeleton\Security\Service\PermissionService;
use Alpabit\ApiSkeleton\Util\StringUtil;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class PermissionGenerator extends AbstractGenerator
{
    private const ROUTE_PLACEHOLDER = 'alpabit_apiskeleton_%s_getall';

    private $entityManager;

    private $permissionService;

    private $menuService;

    private $groupService;

    private $class;

    public function __construct(
        EntityManagerInterface $entityManager,
        PermissionService $permissionService,
        MenuService $menuService,
        GroupService $groupService,
        Environment $twig,
        Filesystem $fileSystem,
        KernelInterface $kernel,
        string $class
    ) {
        $this->entityManager = $entityManager;
        $this->permissionService = $permissionService;
        $this->menuService = $menuService;
        $this->groupService = $groupService;
        $this->class = $class;

        parent::__construct($twig, $fileSystem, $kernel);
    }

    public function generate(\ReflectionClass $entityClass): void
    {
        /** @var MenuInterface $menu */
        $menu = new $this->class();
        $menu->setCode(StringUtil::uppercase($entityClass->getShortName()));
        $menu->setName(StringUtil::uppercase($entityClass->getShortName()));
        $menu->setRouteName(sprintf(static::ROUTE_PLACEHOLDER, $entityClass->getShortName()));

        $this->menuService->save($menu);

        $this->entityManager->getFilters()->disable(PermissionService::FILTER_NAME);
        $this->permissionService->initiate($menu);
        $this->entityManager->getFilters()->enable(PermissionService::FILTER_NAME);

        $superGroup = $this->groupService->getSuperAdmin();
        if ($superGroup && $permission = $this->permissionService->getPermission($superGroup, $menu)) {
            $permission->setAddable(true);
            $permission->setEditable(true);
            $permission->setDeletable(true);
            $permission->setViewable(true);

            $this->permissionService->save($permission);
        }
    }
}
