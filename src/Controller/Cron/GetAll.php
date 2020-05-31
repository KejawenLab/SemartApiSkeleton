<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Controller\Cron;

use Cron\CronBundle\Entity\CronJob;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\Semart\ApiSkeleton\Cron\CronService;
use KejawenLab\Semart\ApiSkeleton\Pagination\Paginator;
use KejawenLab\Semart\ApiSkeleton\Security\Annotation\Permission;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Psr\Log\LoggerInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Permission(menu="CRONJOB", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class GetAll extends AbstractFOSRestController
{
    private $service;

    private $paginator;

    private $logger;

    public function __construct(CronService $service, Paginator $paginator, LoggerInterface $auditLogger)
    {
        $this->service = $service;
        $this->paginator = $paginator;
        $this->logger = $auditLogger;
    }

    /**
     * @Rest\Get("/cronjobs")
     *
     * @SWG\Tag(name="Cron Job")
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
     *     description="Search cron job by code or name"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Return cron job list",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=CronJob::class, groups={"read"}))
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

        return $this->view($this->paginator->paginate($this->service->getQueryBuilder(), $request, CronJob::class));
    }
}
