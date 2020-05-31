<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Cron\Query;

use Cron\CronBundle\Entity\CronJob;
use Alpabit\ApiSkeleton\Pagination\AbstractQueryExtension as Base;

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
