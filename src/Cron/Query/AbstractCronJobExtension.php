<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Cron\Query;

use Alpabit\ApiSkeleton\Pagination\Query\AbstractQueryExtension as Base;
use Cron\CronBundle\Entity\CronJob;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
abstract class AbstractCronJobExtension extends Base
{
    public function support(string $class): bool
    {
        return CronJob::class === $class;
    }
}
