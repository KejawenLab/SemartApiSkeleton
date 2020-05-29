<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Cron\Query;

use Cron\CronBundle\Entity\CronReport;
use KejawenLab\Semart\ApiSkeleton\Pagination\AbstractQueryExtension as Base;

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
