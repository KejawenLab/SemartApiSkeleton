<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cron;

use Redis;
use Cron\Executor\Executor as Base;
use Cron\Report\CronReport;
use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use KejawenLab\ApiSkeleton\Cron\Model\CronRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\Store\RedisStore;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Executor extends Base
{
    public function __construct(
        private Redis $redis,
        private CronRepositoryInterface $repository,
        private CronService $service,
        private LoggerInterface $logger
    )
    {
    }

    protected function startProcesses(CronReport $report): void
    {
        foreach ($this->sets as $set) {
            /** @var CronInterface $cron */
            $cron = $set->getJob()->getCron();
            $key = sprintf('%s_%s', CronInterface::CRON_RUN_KEY, $cron->getId());
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
