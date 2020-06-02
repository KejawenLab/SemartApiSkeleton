<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Cron;

use Cron\Executor\Executor as Base;
use Cron\Report\CronReport;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Executor extends Base
{
    private $measurement;

    public function __construct(Measurement $measurement)
    {
        $this->measurement = $measurement;
    }

    protected function startProcesses(CronReport $report): void
    {
        foreach ($this->sets as $set) {
            $this->measurement->makeSure($set, $report);
        }
    }
}
