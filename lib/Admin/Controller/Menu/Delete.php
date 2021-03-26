<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Menu;

use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\ApiSkeleton\Security\Service\MenuService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="MENU", actions={Permission::DELETE})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Delete extends AbstractController
{
    private MenuService $service;

    public function __construct(MenuService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/menus/{id}/delete", methods={"GET"})
     */
    public function __invoke(string $id): Response
    {
        $menu = $this->service->get($id);
        if (!$menu instanceof MenuInterface) {
            $this->addFlash('error', 'sas.page.menu.not_found');

            return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_menu_getall__invoke'));
        }

        $this->service->remove($menu);

        $this->addFlash('info', 'sas.page.menu.deleted');

        return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_menu_getall__invoke'));
    }
}