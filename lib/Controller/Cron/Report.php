<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Cron;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Cron\CronReportService;
use KejawenLab\ApiSkeleton\Entity\CronReport;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Permission(menu="CRON", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Report extends AbstractFOSRestController
{
    public function __construct(private readonly CronReportService $service, private readonly Paginator $paginator)
    {
    }

    /**
     *
     * @Cache(expires="+1 minute", public=false)
     *
     * @OA\Tag(name="Cron")
     * @OA\Response(
     *     response=200,
     *     description= "Cron report list",
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(ref=@Model(type=CronReport::class, groups={"read"}))
     *             )
     *         )
     *     }
     * )
     *
     * @Security(name="Bearer")
     */
    #[Get(data: '/cronjobs/{id}/logs', name: Report::class, priority: -27)]
    public function __invoke(Request $request, string $id): View
    {
        return $this->view($this->paginator->paginate($this->service->getQueryBuilder(), $request, CronReport::class));
    }
}
