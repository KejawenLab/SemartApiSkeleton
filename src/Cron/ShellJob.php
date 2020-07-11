<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Cron;

use Alpabit\ApiSkeleton\Cron\Model\CronInterface;
use Cron\Job\ShellJob as Base;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class ShellJob extends Base
{
    private CronInterface $cron;

    public function __construct(CronInterface $cron)
    {
        $this->cron = $cron;
    }

    public function getCron(): CronInterface
    {
        return $this->cron;
    }
}
