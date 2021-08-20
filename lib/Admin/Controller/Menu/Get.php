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
use ReflectionClass;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="MENU", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Get extends AbstractController
{
    public function __construct(private MenuService $service, private AuditService $audit, private Reader $reader)
    {
        parent::__construct($this->service);
    }

    /**
     * @Route("/menus/{id}", name=Get::class, methods={"GET"})
     */
    public function __invoke(string $id): Response
    {
        $menu = $this->service->get($id);
        if (!$menu instanceof MenuInterface) {
            $this->addFlash('error', 'sas.page.menu.not_found');

            return new RedirectResponse($this->generateUrl(Main::class));
        }

        $audit = new Record($menu);
        if ($this->reader->getProvider()->isAuditable(Menu::class)) {
            $audit = $this->audit->getAudits($menu, $id, 3);
        }

        return $this->renderDetail($audit, new ReflectionClass(Menu::class));
    }
}
