<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class CronStartCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('semart:cron:start')
            ->setDescription('Starts cron scheduler')
            ->addOption('blocking', 'b', InputOption::VALUE_NONE, 'Run in blocking mode.');
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Starting cron scheduler</info>');
        $this->scheduler($output->isVerbose() ? $output : new NullOutput());

        return 0;
    }

    /**
     * @throws Exception
     */
    private function scheduler(OutputInterface $output): void
    {
        $input = new ArrayInput([]);

        $console = $this->getApplication();
        $command = $console->find('semart:cron:run');
        while (true) {
            $now = microtime(true);
            usleep((int)((60 - ($now % 60) + $now - $now) * 1_000_000.0));

            $command->run($input, $output);
        }
    }
}
