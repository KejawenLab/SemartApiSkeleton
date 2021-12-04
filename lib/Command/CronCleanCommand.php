<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Command;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use KejawenLab\ApiSkeleton\Cron\CronReportService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class CronCleanCommand extends Command
{
    public function __construct(private readonly CronReportService $service)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('semart:cron:clean')
            ->setDescription('Clean stale reports')
        ;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(sprintf('Cleaning %d stale record(s)', $this->service->countStale()));
        $this->service->clean();

        return 0;
    }
}
