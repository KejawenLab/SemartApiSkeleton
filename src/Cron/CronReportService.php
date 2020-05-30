<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Cron;

use KejawenLab\Semart\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\Semart\ApiSkeleton\Repository\CronReportRepository;
use KejawenLab\Semart\ApiSkeleton\Service\AbstractService;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class CronReportService extends AbstractService
{
    public function __construct(CronReportRepository $repository, AliasHelper $aliasHelper)
    {
        parent::__construct($repository, $aliasHelper);
    }
}
