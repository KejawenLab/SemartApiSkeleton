<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Client;

use Alpabit\ApiSkeleton\Entity\Client;
use Alpabit\ApiSkeleton\Client\ClientService;
use Alpabit\ApiSkeleton\Pagination\Paginator;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Alpabit\ApiSkeleton\Security\Annotation\Permission;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Noxlogic\RateLimitBundle\Annotation\RateLimit;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Permission(menu="CLIENT", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class GetAll extends AbstractFOSRestController
{
    private ClientService $service;

    private Paginator $paginator;

    public function __construct(ClientService $service, Paginator $paginator)
    {
        $this->service = $service;
        $this->paginator = $paginator;
    }

    /**
     * @Rest\Get("/clients")
     *
     * @SWG\Tag(name="Client")
     * @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     type="string",
     *     description="Page indicator"
     * )
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="string",
     *     description="Records per page"
     * )
     * @SWG\Parameter(
     *     name="q",
     *     in="query",
     *     type="string",
     *     description="Search client by [change me]"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Return client list",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Client::class, groups={"read"}))
     *     )
     * )
     *
     * @Security(name="Bearer")
     *
     * @RateLimit(limit=7, period=1)
     *
     * @param Request $request
     *
     * @return View
     */
    public function __invoke(Request $request): View
    {
        return $this->view($this->paginator->paginate($this->service->getQueryBuilder(), $request, Client::class));
    }
}
