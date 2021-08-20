<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Menu;

use KejawenLab\ApiSkeleton\Admin\Controller\AbstractController;
use KejawenLab\ApiSkeleton\Entity\Menu;
use KejawenLab\ApiSkeleton\Form\MenuType;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Service\MenuService;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="MENU", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class GetAll extends AbstractController
{
    public function __construct(private MenuService $service, Paginator $paginator)
    {
        parent::__construct($this->service, $paginator);
    }

    /**
     * @Route("/menus", name=GetAll::class, methods={"GET", "POST"})
     */
    public function __invoke(Request $request): Response
    {
        $menu = new Menu();
        $flashs = $request->getSession()->getFlashBag()->get('id');
        foreach ($flashs as $flash) {
            $menu = $this->service->get($flash);
            if ($menu) {
                $this->addFlash('id', $menu->getId());

                break;
            }
        }

        $form = $this->createForm(MenuType::class, $menu);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->service->save($menu);
                $this->addFlash('info', 'sas.page.menu.saved');
            }
        }

        return $this->renderList($form, $request, new ReflectionClass(Menu::class));
    }
}
