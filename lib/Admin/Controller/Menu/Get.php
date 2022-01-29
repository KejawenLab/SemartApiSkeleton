<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Menu;

use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use KejawenLab\ApiSkeleton\Admin\Controller\AbstractController;
use KejawenLab\ApiSkeleton\Audit\Audit as Record;
use KejawenLab\ApiSkeleton\Audit\AuditService;
use KejawenLab\ApiSkeleton\Entity\Menu;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\ApiSkeleton\Security\Service\MenuService;
use Psr\Cache\CacheItemPoolInterface;
use ReflectionClass;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'MENU', actions: [Permission::VIEW])]
final class Get extends AbstractController
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly MenuService $service,
        private readonly CacheItemPoolInterface $cache,
        private readonly AuditService $audit,
        private readonly Reader $reader,
    ) {
        parent::__construct($this->requestStack->getCurrentRequest(), $this->service, $this->cache);
    }
    #[Route(path: '/menus/{id}', name: Get::class, methods: ['GET'])]
    public function __invoke(string $id): Response
    {
        $menu = $this->service->get($id);
        if (!$menu instanceof MenuInterface) {
            $this->addFlash('error', 'sas.page.menu.not_found');

            return new RedirectResponse($this->generateUrl(Main::class));
        }

        $audit = new Record($menu);
        if ($this->reader->getProvider()->isAuditable(Menu::class)) {
            $audit = $this->audit->getAudits($menu, $id, 1);
        }

        return $this->renderDetail($audit, new ReflectionClass(Menu::class));
    }
}
