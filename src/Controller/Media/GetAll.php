<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Media;

use Alpabit\ApiSkeleton\Entity\Media;
use Alpabit\ApiSkeleton\Media\MediaService;
use Alpabit\ApiSkeleton\Pagination\Paginator;
use Alpabit\ApiSkeleton\Security\Annotation\Permission;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Psr\Log\LoggerInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Permission(menu="MEDIA", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
*/
final class GetAll extends AbstractFOSRestController
{
    private MediaService $service;

    private Paginator $paginator;

    private LoggerInterface $logger;

    public function __construct(MediaService $service, Paginator $paginator, LoggerInterface $auditLogger)
    {
        $this->service = $service;
        $this->paginator = $paginator;
        $this->logger = $auditLogger;
    }

    /**
    * @Rest\Get("/medias")
    *
    * @SWG\Tag(name="Media")
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
    *     description="Search media by [change me]"
    * )
    * @SWG\Response(
    *     response=200,
    *     description="Return media list",
    *     @SWG\Schema(
    *         type="array",
    *         @SWG\Items(ref=@Model(type=Media::class, groups={"read"}))
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

        return $this->view($this->paginator->paginate($this->service->getQueryBuilder(), $request, Media::class));
    }
}
