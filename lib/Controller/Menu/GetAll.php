<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Menu;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\Menu;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Service\MenuService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Permission(menu="MENU", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class GetAll extends AbstractFOSRestController
{
    private MenuService $service;

    private Paginator $paginator;

    public function __construct(MenuService $service, Paginator $paginator)
    {
        $this->service = $service;
        $this->paginator = $paginator;
    }

    /**
     * @Rest\Get("/menus")
     *
     * @OA\Tag(name="Menu")
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format="int32"
     *     )
     * )
     * @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format="int32"
     *     )
     * )
     * @OA\Parameter(
     *     name="q",
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *     )
     * )
     * @OA\Parameter(
     *     name="code",
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format="int32"
     *     ),
     *     description="Filter menu by code"
     * )
     * @OA\Response(
     *     response=200,
     *     description= "Menu list",
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(ref=@Model(type=Menu::class, groups={"read"}))
     *             )
     *         )
     *     }
     * )
     *
     * @Security(name="Bearer")
     */
    public function __invoke(Request $request): View
    {
        return $this->view($this->paginator->paginate($this->service->getQueryBuilder(), $request, Menu::class));
    }
}
