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
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'MENU', actions: [Permission::DELETE])]
final class Delete extends AbstractController
{
    public function __construct(private readonly MenuService $service)
    {
    }
    #[Route(path: '/menus/{id}/delete', name: Delete::class, methods: ['GET'])]
    public function __invoke(string $id): Response
    {
        $menu = $this->service->get($id);
        if (!$menu instanceof MenuInterface) {
            $this->addFlash('error', 'sas.page.menu.not_found');

            return new RedirectResponse($this->generateUrl(Main::class));
        }

        $this->service->remove($menu);

        $this->addFlash('info', 'sas.page.menu.deleted');

        return new RedirectResponse($this->generateUrl(Main::class));
    }
}
