<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Cron;

use Alpabit\ApiSkeleton\Cron\Model\CronInterface;
use Cron\Job\ShellJob as Base;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class ShellJob extends Base
{
    private $cron;

    public function getCron(): CronInterface
    {
        return $this->cron;
    }

    public function setCron(CronInterface $cron): void
    {
        $this->cron = $cron;
    }
}
