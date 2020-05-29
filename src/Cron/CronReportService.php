<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Cron;

use KejawenLab\Semart\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\Semart\ApiSkeleton\Repository\CronReportRepository;
use KejawenLab\Semart\ApiSkeleton\Service\AbstractService;
use KejawenLab\Semart\ApiSkeleton\Util\Serializer;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class CronReportService extends AbstractService
{
    public function __construct(CronReportRepository $repository, Serializer $serializer, AliasHelper $aliasHelper)
    {
        parent::__construct($repository, $serializer, $aliasHelper);
    }
}
