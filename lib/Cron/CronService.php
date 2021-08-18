<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cron;

use Cron\Resolver\ResolverInterface;
use Cron\Schedule\CrontabSchedule;
use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use KejawenLab\ApiSkeleton\Cron\Model\CronRepositoryInterface;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Service\AbstractService;
use KejawenLab\ApiSkeleton\Service\Model\ServiceInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class CronService extends AbstractService implements ServiceInterface, ResolverInterface
{
    public function __construct(
        MessageBusInterface $messageBus,
        CronRepositoryInterface $repository,
        AliasHelper $aliasHelper,
        private KernelInterface $kernel,
        private CronBuilder $builder
    ) {
        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    /**
     * @return ShellJob[]
     */
    public function resolve(): array
    {
        return array_map(function (CronInterface $cron): ShellJob {
            $job = new ShellJob($cron);
            $job->setCommand((string) $this->builder->build($cron), $this->kernel->getProjectDir());
            $job->setSchedule(new CrontabSchedule($cron->getSchedule()));

            return $job;
        }, $this->repository->findBy(['enabled' => true, 'running' => false], ['name' => 'ASC']));
    }
}
