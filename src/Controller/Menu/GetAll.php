<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Menu;

use Alpabit\ApiSkeleton\Entity\Menu;
use Alpabit\ApiSkeleton\Pagination\Paginator;
use Alpabit\ApiSkeleton\Security\Annotation\Permission;
use Alpabit\ApiSkeleton\Security\Service\MenuService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Psr\Log\LoggerInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Permission(menu="MENU", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class GetAll extends AbstractFOSRestController
{
    private $service;

    private $paginator;

    private $logger;

    public function __construct(MenuService $service, Paginator $paginator, LoggerInterface $auditLogger)
    {
        $this->service = $service;
        $this->paginator = $paginator;
        $this->logger = $auditLogger;
    }

    /**
     * @Rest\Get("/menus")
     *
     * @SWG\Tag(name="Menu")
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
     *     description="Search menu by code or name"
     * )
     * @SWG\Parameter(
     *     name="code",
     *     in="query",
     *     type="string",
     *     description="Filter menu by code"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Return menu list",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Menu::class, groups={"read"}))
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

        return $this->view($this->paginator->paginate($this->service->getQueryBuilder(), $request, Menu::class));
    }
}
