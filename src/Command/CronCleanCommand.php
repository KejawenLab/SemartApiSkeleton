<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Command;

use KejawenLab\ApiSkeleton\Cron\CronReportService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
class CronCleanCommand extends Command
{
    private CronReportService $service;

    public function __construct(CronReportService $service)
    {
        $this->service = $service;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('semart:cron:clean')
            ->setDescription('Clean stale reports')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('Cleaning %d stale record(s)', $this->service->countStale()));
        $this->service->clean();

        return 0;
    }
}
