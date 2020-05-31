<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Cron\Query;

use Alpabit\ApiSkeleton\Pagination\AbstractQueryExtension as Base;
use Cron\CronBundle\Entity\CronReport;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
abstract class AbstractCronReportExtension extends Base
{
    public function support(string $class): bool
    {
        return CronReport::class === $class;
    }
}
