<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cron;

use Cron\Job\ShellJob as Base;
use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;

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
