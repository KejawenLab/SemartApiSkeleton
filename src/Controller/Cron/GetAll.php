<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Cron;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Cron\CronService;
use KejawenLab\ApiSkeleton\Entity\Cron;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Permission(menu="CRON", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class GetAll extends AbstractFOSRestController
{
    private CronService $service;

    private Paginator $paginator;

    public function __construct(CronService $service, Paginator $paginator)
    {
        $this->service = $service;
        $this->paginator = $paginator;
    }

    /**
     * @Rest\Get("/cronjobs", priority=-7)
     *
     * @OA\Tag(name="Cron")
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format="int32"
     *     ),
     *     description="Page indicator"
     * )
     * @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format="int32"
     *     ),
     *     description="Records per page"
     * )
     * @OA\Parameter(
     *     name="q",
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format="int32"
     *     ),
     *     description="Search cron by name or command"
     * )
     * @OA\Response(
     *     response=200,
     *     description= "Api client list",
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(ref=@Model(type=Cron::class, groups={"read"}))
     *             )
     *         )
     *     }
     * )
     *
     * @Security(name="Bearer")
     */
    public function __invoke(Request $request): View
    {
        return $this->view($this->paginator->paginate($this->service->getQueryBuilder(), $request, Cron::class));
    }
}
