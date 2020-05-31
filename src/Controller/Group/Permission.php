<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Group;

use Alpabit\ApiSkeleton\Entity\Permission as Entity;
use Alpabit\ApiSkeleton\Pagination\Paginator;
use Alpabit\ApiSkeleton\Security\Annotation as Semart;
use Alpabit\ApiSkeleton\Security\Service\PermissionService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Psr\Log\LoggerInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Semart\Permission(menu="GROUP", actions={Semart\Permission::ADD, Semart\Permission::EDIT})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Permission extends AbstractFOSRestController
{
    private $service;

    private $paginator;

    private $logger;

    public function __construct(PermissionService $service, Paginator $paginator, LoggerInterface $auditLogger)
    {
        $this->service = $service;
        $this->paginator = $paginator;
        $this->logger = $auditLogger;
    }

    /**
     * @Rest\Get("/groups/{id}/permissions")
     *
     * @SWG\Tag(name="Group")
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
     * @SWG\Response(
     *     response=200,
     *     description="Return permission list of group",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Entity::class, groups={"read"}))
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
        $this->logger->info(sprintf('[%s][%s][%s]', $this->getUser()->getUsername(), __CLASS__, serialize($request->query->all())));

        return $this->view($this->paginator->paginate($this->service->getQueryBuilder(), $request, Entity::class));
    }
}
