<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Group;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\Group;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Service\GroupService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Noxlogic\RateLimitBundle\Annotation\RateLimit;
use OpenApi\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Permission(menu="GROUP", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class GetAll extends AbstractFOSRestController
{
    private GroupService $service;

    private Paginator $paginator;

    public function __construct(GroupService $service, Paginator $paginator)
    {
        $this->service = $service;
        $this->paginator = $paginator;
    }

    /**
     * @Rest\Get("/groups")
     *
     * @SWG\Tag(name="Group")
     * @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     @SWG\Schema(
     *         type="integer",
     *         format="int32"
     *     ),
     *     description="Page indicator"
     * )
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     @SWG\Schema(
     *         type="integer",
     *         format="int32"
     *     ),
     *     description="Records per page"
     * )
     * @SWG\Parameter(
     *     name="q",
     *     in="query",
     *     @SWG\Schema(
     *         type="integer",
     *         format="int32"
     *     ),
     *     description="Search group by code or name"
     * )
     * @SWG\Parameter(
     *     name="code",
     *     in="query",
     *     @SWG\Schema(
     *         type="integer",
     *         format="int32"
     *     ),
     *     description="Filter group by code"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Return group list",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Group::class, groups={"read"}))
     *     )
     * )
     *
     * @RateLimit(limit=7, period=1)
     *
     * @Security(name="Bearer")
     */
    public function __invoke(Request $request): View
    {
        return $this->view($this->paginator->paginate($this->service->getQueryBuilder(), $request, Group::class));
    }
}
