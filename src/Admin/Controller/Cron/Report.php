<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Cron;

use KejawenLab\ApiSkeleton\Cron\CronReportService;
use KejawenLab\ApiSkeleton\Entity\CronReport;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="CRON", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Report extends AbstractController
{
    private CronReportService $service;

    private Paginator $paginator;

    public function __construct(CronReportService $service, Paginator $paginator)
    {
        $this->service = $service;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/cron/{id}/logs", methods={"GET"}, priority=-27)
     */
    public function __invoke(Request $request): Response
    {
        $class = new \ReflectionClass(CronReport::class);

        return $this->render('cron/report.html.twig', [
            'page_title' => 'sas.page.cron_report.list',
            'context' => StringUtil::lowercase($class->getShortName()),
            'properties' => $class->getProperties(\ReflectionProperty::IS_PRIVATE),
            'paginator' => $this->paginator->paginate($this->service->getQueryBuilder(), $request, CronReport::class),
        ]);
    }
}
