<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Media;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\Media;
use KejawenLab\ApiSkeleton\Media\MediaService;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Permission(menu="MEDIA", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class GetAll extends AbstractFOSRestController
{
    public function __construct(private MediaService $service, private Paginator $paginator)
    {
    }

    /**
     * @OA\Tag(name="Media")
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
     * @OA\Response(
     *     response=200,
     *     description= "Media list",
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(ref=@Model(type=Media::class, groups={"read"}))
     *             )
     *         )
     *     }
     * )
     *
     * @Security(name="Bearer")
     */
    #[Get(data: '/medias', name: GetAll::class)]
    public function __invoke(Request $request): View
    {
        return $this->view($this->paginator->paginate($this->service->getQueryBuilder(), $request, Media::class));
    }
}
