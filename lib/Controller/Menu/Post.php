<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Menu;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Post as Route;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\Menu;
use KejawenLab\ApiSkeleton\Form\FormFactory;
use KejawenLab\ApiSkeleton\Form\MenuType;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\ApiSkeleton\Security\Service\MenuService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Permission(menu="MENU", actions={Permission::ADD})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Post extends AbstractFOSRestController
{
    public function __construct(private FormFactory $formFactory, private MenuService $service)
    {
    }

    /**
     * @OA\Tag(name="Menu")
     * @OA\RequestBody(
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 ref=@Model(type=MenuType::class)
     *             )
     *         )
     *     }
     * )
     * @OA\Response(
     *     response=201,
     *     description= "Menu created",
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 ref=@Model(type=Menu::class, groups={"read"})
     *             )
     *         )
     *     }
     * )
     *
     * @Security(name="Bearer")
     */
    #[Route(data: '/menus', name: Post::class)]
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
