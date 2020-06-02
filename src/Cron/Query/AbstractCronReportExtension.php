<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Cron\Query;

use Alpabit\ApiSkeleton\Cron\Model\CronReportInterface;
use Alpabit\ApiSkeleton\Pagination\Query\AbstractQueryExtension as Base;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
abstract class AbstractCronReportExtension extends Base
{
    public function support(string $class): bool
    {
        return in_array(CronReportInterface::class, class_implements($class));
    }
}
