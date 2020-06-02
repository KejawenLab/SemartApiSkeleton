<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Cron;

use Alpabit\ApiSkeleton\Cron\Model\CronInterface;
use Alpabit\ApiSkeleton\Cron\Model\CronRepositoryInterface;
use Alpabit\ApiSkeleton\Pagination\AliasHelper;
use Alpabit\ApiSkeleton\Service\AbstractService;
use Alpabit\ApiSkeleton\Service\Model\ServiceInterface;
use Cron\Resolver\ResolverInterface;
use Cron\Schedule\CrontabSchedule;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
*/
final class CronService extends AbstractService implements ServiceInterface, ResolverInterface
{
    private $kernel;

    private $builder;

    public function __construct(
        CronRepositoryInterface $repository,
        AliasHelper $aliasHelper,
        KernelInterface $kernel,
        CronBuilder $builder
    ) {
        $this->kernel = $kernel;
        $this->builder = $builder;

        parent::__construct($repository, $aliasHelper);
    }

    public function resolve(): array
    {
        return array_map(function (CronInterface $cron) {
            $job = new ShellJob();
            $job->setCron($cron);
            $job->setCommand($this->builder->build($cron), $this->kernel->getProjectDir());
            $job->setSchedule(new CrontabSchedule($cron->getSchedule()));

            return $job;
        }, $this->repository->findBy(['enabled' => true, 'running' => false], ['name' => 'ASC']));
    }
}
