<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Cron;

use Alpabit\ApiSkeleton\Cron\Model\CronInterface;
use Alpabit\ApiSkeleton\Cron\Model\CronRepositoryInterface;
use Cron\Executor\ExecutorSet;
use Cron\Report\CronReport;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Measurement
{
    private $redis;

    private $manager;

    private $repository;

    private $service;

    private $logger;

    public function __construct(
        \Redis $redis,
        EntityManagerInterface $manager,
        CronRepositoryInterface $repository,
        CronService $service,
        LoggerInterface $dbLogger
    ) {
        $this->redis = $redis;
        $this->manager = $manager;
        $this->repository = $repository;
        $this->service = $service;
        $this->logger = $dbLogger;
    }

    public function makeSure(ExecutorSet $set, CronReport $report): void
    {
        try {
            /** @var CronInterface $cron */
            $cron = $set->getJob()->getCron();
            $key = sprintf('%s_%s_', CronInterface::CRON_RUN_KEY, $cron->getId());
            if ($this->redis->get($key) || $this->isRunning($cron)) {
                return;
            }

            $this->redis->set($key, 1);
            $this->redis->expire($key, $cron->getEstimation() * 60);

            $cron->setRunning(true);
            $this->service->save($cron);

            $report->addJobReport($set->getReport());
            $set->run();

            $this->manager->flush();
        } catch (\Exception $e) {
            $this->logger->critical(sprintf('Optimistic Lock Problem: %s', $e->getMessage()));
        }
    }

    private function isRunning(CronInterface $cron): bool
    {
        /** @var CronInterface $cron */
        $cron = $this->repository->find($cron->getId(), LockMode::OPTIMISTIC);
        if ($cron && $cron->isRunning()) {
            return true;
        }

        return false;
    }
}
