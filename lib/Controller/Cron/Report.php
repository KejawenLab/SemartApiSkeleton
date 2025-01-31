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
use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'CRON', actions: [Permission::VIEW])]
#[Tag(name: 'Cron')]
final class Report extends AbstractFOSRestController
{
    public function __construct(private readonly CronReportService $service, private readonly Paginator $paginator)
    {
    }

    #[Get(data: '/cronjobs/{id}/logs', name: self::class, priority: -27)]
    #[Security(name: 'Bearer')]
    #[Response(
        response: 200,
        description: 'Cron report list',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'array',
                items: new OA\Items(ref: new Model(type: CronReport::class, groups: ['read'])),
            ),
        ),
    )]
    public function __invoke(Request $request, string $id): View
    {
        return $this->view($this->paginator->paginate($this->service->getQueryBuilder(), $request, CronReport::class));
    }
}
