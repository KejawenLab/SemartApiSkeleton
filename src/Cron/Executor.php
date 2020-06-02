<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Cron;

use Alpabit\ApiSkeleton\Cron\Model\CronInterface;
use Cron\Executor\Executor as Base;
use Cron\Report\CronReport;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Executor extends Base
{
    private $service;

    private $redis;

    public function __construct(CronService $service, \Redis $redis)
    {
        $this->service = $service;
        $this->redis = $redis;
    }

    protected function startProcesses(CronReport $report): void
    {
        foreach ($this->sets as $set) {
            /** @var CronInterface $cron */
            $cron = $set->getJob()->getCron();
            $cron->setRunning(true);
            $key = sprintf('%s_%s_', CronInterface::CRON_RUN_KEY, $cron->getId());
            if ($this->redis->get($key)) {
                dump($key);
            }

            $this->redis->set($key, 1);
            $this->redis->expire($key, 7200);
            $this->service->save($cron);

            $report->addJobReport($set->getReport());
            $set->run();
        }
    }
}
