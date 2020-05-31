<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Cron;

use Alpabit\ApiSkeleton\Pagination\AliasHelper;
use Alpabit\ApiSkeleton\Repository\CronReportRepository;
use Alpabit\ApiSkeleton\Service\AbstractService;

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
