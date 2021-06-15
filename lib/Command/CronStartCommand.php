<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Command;

use RuntimeException;
use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class CronStartCommand extends Command
{
    protected function configure()
    {
        $this->setName('semart:cron:start')
            ->setDescription('Starts cron scheduler')
            ->addOption('blocking', 'b', InputOption::VALUE_NONE, 'Run in blocking mode.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('blocking')) {
            $output->writeln('<info>Starting cron scheduler in blocking mode</info>');
            $this->scheduler($output->isVerbose() ? $output : new NullOutput(), null);

            return 0;
        }

        $pidFile = sys_get_temp_dir().DIRECTORY_SEPARATOR.CronInterface::PID_FILE;
        if (-1 === $pid = pcntl_fork()) {
            throw new RuntimeException('Unable to start the cron process');
        } elseif (0 !== $pid) {
            if (false === file_put_contents($pidFile, $pid)) {
                throw new RuntimeException('Unable to create process file');
            }

            $output->writeln('<info>Cron scheduler started in non-blocking mode</info>');

            return 0;
        }

        if (-1 === posix_setsid()) {
            throw new RuntimeException('Unable to set the child process as session leader');
        }

        $this->scheduler(new NullOutput(), $pidFile);

        return 0;
    }

    private function scheduler(OutputInterface $output, $pidFile)
    {
        $input = new ArrayInput([]);

        $console = $this->getApplication();
        $command = $console->find('semart:cron:run');
        while (true) {
            $now = microtime(true);
            usleep((int) ((60 - ($now % 60) + (int) $now - $now) * 1e6));

            if (null !== $pidFile && !file_exists($pidFile)) {
                break;
            }

            $command->run($input, $output);
        }
    }
}
