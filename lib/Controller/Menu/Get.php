<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Menu;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\Menu;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\ApiSkeleton\Security\Service\MenuService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Permission(menu="MENU", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Get extends AbstractFOSRestController
{
    public function __construct(private MenuService $service, private TranslatorInterface $translator)
    {
    }

    /**
     * @Rest\Get("/menus/{id}", name=Get::class)
     *
     * @OA\Tag(name="Menu")
     * @OA\Response(
     *     response=200,
     *     description= "Menu detail",
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
    public function __invoke(string $id): View
    {
        $menu = $this->service->get($id);
        if (!$menu instanceof MenuInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.menu.not_found', [], 'pages'));
        }

        return $this->view($menu);
    }
}
