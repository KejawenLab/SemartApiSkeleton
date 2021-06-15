<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Command;

use KejawenLab\ApiSkeleton\Application\App;
use KejawenLab\ApiSkeleton\Upgrade\Model\UpgradeInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class UpgradeCommand extends Command
{
    public function __construct(
    /**
     * @var UpgradeInterface[]
     */
    private iterable $upgraders)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('semart:upgrade')
            ->setDescription('Upgrade Application')
            ->addOption('force', 'f', InputOption::VALUE_NONE)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->write('<fg=green;options=bold>
   _____                           __     __  __                           __
  / ___/___  ____ ___  ____ ______/ /_   / / / /___  ____ __________ _____/ /__  _____
  \__ \/ _ \/ __ `__ \/ __ `/ ___/ __/  / / / / __ \/ __ `/ ___/ __ `/ __  / _ \/ ___/
 ___/ /  __/ / / / / / /_/ / /  / /_   / /_/ / /_/ / /_/ / /  / /_/ / /_/ /  __/ /
/____/\___/_/ /_/ /_/\__,_/_/   \__/   \____/ .___/\__, /_/   \__,_/\__,_/\___/_/
                                           /_/    /____/
By: KejawenLab - Muhamad Surya Iksanudin<<comment>surya.kejawen@gmail.com</comment>>

</>');
        if (!$input->getOption('force')) {
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion('<comment>[!!!WARNING!!!]</comment><question> Upgrade software can\'t be rolled back. Continue?</question> (y/n)', false);
            if (!$helper->ask($input, $output, $question)) {
                return 0;
            }
        }

        $output->writeln('<comment>Running upgrade</comment>');
        $version = App::getVersionNumber();
        foreach ($this->upgraders as $upgrader) {
            if ($upgrader->support($version)) {
                $upgrader->upgrade();
            }
        }

        $output->writeln('<comment>Upgrade is finished</comment>');

        return 0;
    }
}
