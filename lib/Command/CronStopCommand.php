<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Command;

use RuntimeException;
use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class CronStopCommand extends Command
{
    protected function configure()
    {
        $this->setName('semart:cron:stop')->setDescription('Stops cron scheduler');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pidFile = sys_get_temp_dir().DIRECTORY_SEPARATOR.CronInterface::PID_FILE;
        if (!file_exists($pidFile)) {
            return 0;
        }

        if (!posix_kill((int) file_get_contents($pidFile), SIGINT)) {
            if (!unlink($pidFile)) {
                throw new RuntimeException('Unable to stop scheduler.');
            }

            $output->writeln('<comment>Unable to kill cron scheduler process. Scheduler will be stopped before the next run</comment>');

            return 0;
        }

        unlink($pidFile);

        $output->writeln('<info>Cron scheduler is stopped</info>');

        return 0;
    }
}
