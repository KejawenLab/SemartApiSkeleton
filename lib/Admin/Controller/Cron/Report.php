<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Cron;

use KejawenLab\ApiSkeleton\Cron\CronReportService;
use KejawenLab\ApiSkeleton\Entity\CronReport;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="CRON", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Report extends AbstractController
{
    public function __construct(private CronReportService $service, private Paginator $paginator, private SettingService $settingService)
    {
    }

    /**
     * @Route(path="/crons/{id}/logs", name=Report::class, methods={"GET"}, priority=-27)
     */
    public function __invoke(Request $request, string $id): Response
    {
        $class = new ReflectionClass(CronReport::class);

        $request->query->set($this->settingService->getPerPageField(), 10);

        return $this->render('cron/report.html.twig', [
            'page_title' => 'sas.page.cron_report.list',
            'id' => $id,
            'context' => StringUtil::lowercase($class->getShortName()),
            'properties' => $class->getProperties(ReflectionProperty::IS_PRIVATE),
            'paginator' => $this->paginator->paginate($this->service->getQueryBuilder(), $request, CronReport::class),
        ]);
    }
}
