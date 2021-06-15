<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator;

use ReflectionClass;
use Doctrine\ORM\EntityManagerInterface;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\ApiSkeleton\Security\Service\GroupService;
use KejawenLab\ApiSkeleton\Security\Service\MenuService;
use KejawenLab\ApiSkeleton\Security\Service\PermissionService;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class PermissionGenerator extends AbstractGenerator
{
    private const ROUTE_PLACEHOLDER = 'kejawenlab_apiskeleton_application_%s_getall';

    public function __construct(
        private PermissionService $permissionService,
        private MenuService $menuService,
        private GroupService $groupService,
        Environment $twig,
        Filesystem $fileSystem,
        KernelInterface $kernel,
        EntityManagerInterface $entityManager,
        private string $class
    ) {
        parent::__construct($twig, $fileSystem, $kernel, $entityManager);
    }

    public function generate(ReflectionClass $class, OutputInterface $output, ?string $folder = null): void
    {
        $shortName = $class->getShortName();
        $shortNameUpper = StringUtil::uppercase($shortName);
        if ($this->menuService->getMenuByCode($shortNameUpper)) {
            $output->writeln(sprintf('<info>Menu for "%s" already exists. Skipped</info>', $shortName));

            return;
        }

        /** @var MenuInterface $menu */
        $menu = new $this->class();
        $menu->setCode($shortNameUpper);
        $menu->setName($shortNameUpper);
        $menu->setRouteName(sprintf(static::ROUTE_PLACEHOLDER, StringUtil::lowercase($shortName)));

        $this->menuService->save($menu);

        $output->writeln(sprintf('<comment>Generating permission(s) for menu "<info>%s</info></comment>"', $shortNameUpper));
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

    public function support(string $scope): bool
    {
        return static::SCOPE_API === $scope;
    }
}
