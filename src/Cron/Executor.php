<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Cron;

use Alpabit\ApiSkeleton\Cron\Model\CronInterface;
use Alpabit\ApiSkeleton\Cron\Model\CronRepositoryInterface;
use Cron\Executor\Executor as Base;
use Cron\Report\CronReport;
use Psr\Log\LoggerInterface;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\Store\RedisStore;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Executor extends Base
{
    private \Redis $redis;

    private CronRepositoryInterface $repository;

    private CronService $service;

    private LoggerInterface $logger;

    public function __construct(
        \Redis $redis,
        CronRepositoryInterface $repository,
        CronService $service,
        LoggerInterface $cronLogger
    ) {
        $this->redis = $redis;
        $this->repository = $repository;
        $this->service = $service;
        $this->logger = $cronLogger;
    }

    protected function startProcesses(CronReport $report): void
    {
        foreach ($this->sets as $set) {
            /** @var CronInterface $cron */
            $cron = $set->getJob()->getCron();
            $key = sprintf('%s_%s_', CronInterface::CRON_RUN_KEY, $cron->getId());
            $lock = (new LockFactory(new RedisStore($this->redis)))->createLock($key);
            if (!$lock->acquire()) {
                $this->logger->info(sprintf('"%s" schedule is locked by same process in other machine', $cron->getName()));

                return;
            }

            $this->logger->info(sprintf('Locking "%s" schedule', $cron->getName()));
            $cron = $this->repository->find($cron->getId());
            if ($cron->isRunning()) {
                $this->logger->info(sprintf('"%s" schedule is already running in other machine', $cron->getName()));

                return;
            }

            $cron->setRunning(true);
            $this->service->save($cron);

            $report->addJobReport($set->getReport());
            $this->logger->info(sprintf('Running "%s" schedule', $cron->getName()));
            $set->run();

            $lock->release();
        }
    }
}
