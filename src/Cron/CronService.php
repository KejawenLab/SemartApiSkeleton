<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cron;

use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use KejawenLab\ApiSkeleton\Cron\Model\CronRepositoryInterface;
use KejawenLab\ApiSkeleton\Pagination\AliasHelper;
use KejawenLab\ApiSkeleton\Service\AbstractService;
use KejawenLab\ApiSkeleton\Service\Model\ServiceInterface;
use Cron\Resolver\ResolverInterface;
use Cron\Schedule\CrontabSchedule;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class CronService extends AbstractService implements ServiceInterface, ResolverInterface
{
    private KernelInterface $kernel;

    private CronBuilder $builder;

    public function __construct(
        MessageBusInterface $messageBus,
        CronRepositoryInterface $repository,
        AliasHelper $aliasHelper,
        KernelInterface $kernel,
        CronBuilder $builder
    ) {
        $this->kernel = $kernel;
        $this->builder = $builder;

        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    public function resolve(): array
    {
        return array_map(function (CronInterface $cron) {
            $job = new ShellJob($cron);
            $job->setCommand($this->builder->build($cron), $this->kernel->getProjectDir());
            $job->setSchedule(new CrontabSchedule($cron->getSchedule()));

            return $job;
        }, $this->repository->findBy(['enabled' => true, 'running' => false], ['name' => 'ASC']));
    }
}
