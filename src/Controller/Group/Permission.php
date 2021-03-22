<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Group;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\Permission as Entity;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation as Semart;
use KejawenLab\ApiSkeleton\Security\Service\PermissionService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Semart\Permission(menu="GROUP", actions=Semart\Permission::VIEW)
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Permission extends AbstractFOSRestController
{
    private PermissionService $service;

    private Paginator $paginator;

    public function __construct(PermissionService $service, Paginator $paginator)
    {
        $this->service = $service;
        $this->paginator = $paginator;
    }

    /**
     * @Rest\Get("/groups/{id}/permissions")
     *
     * @OA\Tag(name="Group")
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
     * @OA\Response(
     *     response=200,
     *     description= "Permission list",
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(ref=@Model(type=Entity::class, groups={"read"}))
     *             )
     *         )
     *     }
     * )
     *
     * @Security(name="Bearer")
     */
    public function __invoke(Request $request): View
    {
        return $this->view($this->paginator->paginate($this->service->getQueryBuilder(), $request, Entity::class));
    }
}
