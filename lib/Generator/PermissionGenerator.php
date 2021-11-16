<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator;

use Doctrine\ORM\EntityManagerInterface;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\ApiSkeleton\Security\Service\GroupService;
use KejawenLab\ApiSkeleton\Security\Service\MenuService;
use KejawenLab\ApiSkeleton\Security\Service\PermissionService;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use ReflectionClass;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class PermissionGenerator extends AbstractGenerator
{
    private const ROUTE_PLACEHOLDER = 'KejawenLab\\Application\\Controller\\%s\\GetAll';

    public function __construct(
        private PermissionService $permissionService,
        private MenuService $menuService,
        private GroupService $groupService,
        Environment $twig,
        Filesystem $fileSystem,
        KernelInterface $kernel,
        EntityManagerInterface $entityManager,
        private string $class,
    ) {
        parent::__construct($twig, $fileSystem, $kernel, $entityManager);
    }

    public function generate(ReflectionClass $class, OutputInterface $output, ?string $folder = null): void
    {
        $shortName = $class->getShortName();
        $shortNameUpper = StringUtil::uppercase($shortName);
        if (null !== $this->menuService->getMenuByCode($shortNameUpper)) {
            $output->writeln(sprintf('<info>Menu for "%s" already exists. Skipped</info>', $shortName));

            return;
        }

        /** @var MenuInterface $menu */
        $menu = new $this->class();
        $menu->setCode($shortNameUpper);
        $menu->setName($shortNameUpper);
        $menu->setRouteName(sprintf(self::ROUTE_PLACEHOLDER, $shortName));

        $this->menuService->save($menu);

        $output->writeln(sprintf('<comment>Generating permission(s) for menu "<info>%s</info></comment>"', $shortNameUpper));
        $this->permissionService->initiate($menu);

        $superGroup = $this->groupService->getSuperAdmin();
        $permission = $this->permissionService->getPermission($superGroup, $menu);
        if ($superGroup && $permission) {
            $permission->setAddable(true);
            $permission->setEditable(true);
            $permission->setDeletable(true);
            $permission->setViewable(true);

            $this->permissionService->save($permission);
        }
    }

    public function support(string $scope): bool
    {
        return self::SCOPE_API === $scope;
    }
}
