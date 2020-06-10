<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Menu;

use Alpabit\ApiSkeleton\Entity\Menu;
use Alpabit\ApiSkeleton\Form\FormFactory;
use Alpabit\ApiSkeleton\Form\Type\MenuType;
use Alpabit\ApiSkeleton\Security\Annotation\Permission;
use Alpabit\ApiSkeleton\Security\Model\MenuInterface;
use Alpabit\ApiSkeleton\Security\Service\MenuService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Permission(menu="MENU", actions={Permission::ADD})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Post extends AbstractFOSRestController
{
    private FormFactory $formFactory;

    private MenuService $service;

    public function __construct(FormFactory $formFactory, MenuService $service)
    {
        $this->formFactory = $formFactory;
        $this->service = $service;
    }

    /**
     * @Rest\Post("/menus")
     *
     * @SWG\Tag(name="Menu")
     * @SWG\Parameter(
     *     name="menu",
     *     in="body",
     *     type="object",
     *     description="Menu form",
     *     @Model(type=MenuType::class)
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Crate new menu",
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=Menu::class, groups={"read"})
     *     )
     * )
     *
     * @Security(name="Bearer")
     *
     * @param Request $request
     *
     * @return View
     */
    public function __invoke(Request $request): View
    {
        $form = $this->formFactory->submitRequest(MenuType::class, $request);
        if (!$form->isValid()) {
            return $this->view((array) $form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        /** @var MenuInterface $menu */
        $menu = $form->getData();
        $this->service->save($menu);

        return $this->view($this->service->get($menu->getId()), Response::HTTP_CREATED);
    }
}
