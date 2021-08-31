<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Command;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class ResetCommand extends Command
{
    public function __construct(private KernelInterface $kernel, private EntityManagerInterface $entityManager)
    {
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
            $output->writeln('<comment>Semart Reset can not run in production environment</comment>');

            return 0;
        }

        if (!$this->kernel->isDebug()) {
            $output->writeln('<comment>Semart Reset can not run in production environment</comment>');

            return 0;
        }

        if (!$input->getOption('force')) {
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion(
                '<comment>[!!!WARNING!!!]</comment><question> Semart Api Reset will reset your data. Continue?</question> (y/n)',
                false
            );

            if (!$helper->ask($input, $output, $question)) {
                return 0;
            }
        }

        $dbName = $this->entityManager->getConnection()->getDatabase();
        $this->entityManager->getConnection()->executeQuery(
            'SELECT pg_terminate_backend(pid) FROM pg_stat_activity WHERE pid <> pg_backend_pid() AND datname = :db',
            ['db' => $dbName]
        );

        /** @var Application $application */
        $application = $this->getApplication();

        $input = new ArrayInput([
            'command' => 'doctrine:database:drop',
            '--force' => null,
        ]);
        $input->setInteractive(false);
        $dropDatabase = $application->find('doctrine:database:drop');
        $dropDatabase->run($input, $output);

        $input = new ArrayInput(['command' => 'doctrine:database:create']);
        $input->setInteractive(false);
        $createDatabase = $application->find('doctrine:database:create');
        $createDatabase->run($input, $output);

        $input = new ArrayInput(['command' => 'doctrine:migrations:migrate']);
        $input->setInteractive(false);
        $migration = $application->find('doctrine:migrations:migrate');
        $migration->run($input, $output);

        $input = new ArrayInput([
            'command' => 'doctrine:schema:update',
            '--force' => null,
        ]);
        $input->setInteractive(false);
        $schemaUpdater = $application->find('doctrine:schema:update');
        $schemaUpdater->run($input, $output);

        $input = new ArrayInput(['command' => 'doctrine:fixtures:load']);
        $input->setInteractive(false);
        $fixtures = $application->find('doctrine:fixtures:load');
        $fixtures->run($input, $output);

        return 0;
    }
}
