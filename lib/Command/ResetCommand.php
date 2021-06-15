<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Command;

use Exception;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class ResetCommand extends Command
{
    private string $semart;

    public function __construct(private KernelInterface $kernel)
    {
        $this->semart = sprintf('%s%s.semart', $kernel->getProjectDir(), DIRECTORY_SEPARATOR);

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('semart:reset')
            ->setDescription('Reset Database Semart Api Skeleton')
            ->addOption('force', 'f', InputOption::VALUE_NONE)
        ;
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ('dev' !== strtolower($this->kernel->getEnvironment())) {
            return 0;
        }

        if (!$this->kernel->isDebug()) {
            return 0;
        }

        $fileSystem = new Filesystem();
        if (!$fileSystem->exists($this->semart)) {
            return 0;
        }

        if (!$input->getOption('force')) {
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion('<comment>[!!!WARNING!!!]</comment><question> Semart Api Reset will reset your data. Continue?</question> (y/n)', false);
            if (!$helper->ask($input, $output, $question)) {
                return 0;
            }
        }

        /** @var Application $application */
        $application = $this->getApplication();
        $dropDatabase = $application->find('doctrine:database:drop');
        $dropDatabase->run(new ArrayInput([
            'command' => 'doctrine:database:drop',
            '--force' => null,
        ]), $output);

        $createDatabase = $application->find('doctrine:database:create');
        $createDatabase->run(new ArrayInput([
            'command' => 'doctrine:database:create',
        ]), $output);

        $input = new ArrayInput([
            'command' => 'doctrine:schema:update',
            '--force' => null,
            '--no-interaction' => null,
        ]);
        $input->setInteractive(false);
        $migration = $application->find('doctrine:schema:update');
        $migration->run($input, $output);

        $input = new ArrayInput([
            'command' => 'doctrine:fixtures:load',
            '--no-interaction' => null,
        ]);
        $input->setInteractive(false);
        $fixtures = $application->find('doctrine:fixtures:load');
        $fixtures->run($input, $output);

        $fileSystem->dumpFile($this->semart, 1);

        return 0;
    }
}
