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
    public function __construct(private CronInterface $cron)
    {
    }

    public function getCron(): CronInterface
    {
        return $this->cron;
    }
}
