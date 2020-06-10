<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Generator;

use Alpabit\ApiSkeleton\Security\Model\MenuInterface;
use Alpabit\ApiSkeleton\Security\Service\GroupService;
use Alpabit\ApiSkeleton\Security\Service\MenuService;
use Alpabit\ApiSkeleton\Security\Service\PermissionService;
use Alpabit\ApiSkeleton\Util\StringUtil;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class PermissionGenerator extends AbstractGenerator
{
    private const ROUTE_PLACEHOLDER = 'alpabit_apiskeleton_%s_getall';

    private $permissionService;

    private $menuService;

    private $groupService;

    private $class;

    public function __construct(
        PermissionService $permissionService,
        MenuService $menuService,
        GroupService $groupService,
        Environment $twig,
        Filesystem $fileSystem,
        KernelInterface $kernel,
        string $class
    ) {
        $this->permissionService = $permissionService;
        $this->menuService = $menuService;
        $this->groupService = $groupService;
        $this->class = $class;

        parent::__construct($twig, $fileSystem, $kernel);
    }

    public function generate(\ReflectionClass $class, OutputInterface $output): void
    {
        $shortName = $class->getShortName();
        $shortNameUppercase = StringUtil::uppercase($shortName);
        /** @var MenuInterface $menu */
        $menu = new $this->class();
        $menu->setCode($shortNameUppercase);
        $menu->setName($shortNameUppercase);
        $menu->setRouteName(sprintf(static::ROUTE_PLACEHOLDER, $shortName));

        $this->menuService->save($menu);

        $output->writeln(sprintf('<comment>Generating permission(s) for menu "<info>%s</info></comment>"', $shortNameUppercase));
        $this->permissionService->initiate($menu);

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
