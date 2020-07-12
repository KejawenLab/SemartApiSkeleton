<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Cron;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Cron\CronReportService;
use KejawenLab\ApiSkeleton\Entity\CronReport;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Noxlogic\RateLimitBundle\Annotation\RateLimit;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Permission(menu="CRON", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Report extends AbstractFOSRestController
{
    private CronReportService $service;

    private Paginator $paginator;

    public function __construct(CronReportService $service, Paginator $paginator)
    {
        $this->service = $service;
        $this->paginator = $paginator;
    }

    /**
     * @Rest\Get("/cronjobs/{id}/logs")
     *
     * @Cache(expires="+1 minute", public=false)
     *
     * @SWG\Tag(name="Cron")
     * @SWG\Response(
     *     response=200,
     *     description="Return cron job report",
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=CronReport::class, groups={"read"})
     *     )
     * )
     * @Security(name="Bearer")
     *
     * @RateLimit(limit=7, period=1)
     */
    public function __invoke(Request $request, string $id): View
    {
        return $this->view($this->paginator->paginate($this->service->getQueryBuilder(), $request, CronReport::class));
    }
}
