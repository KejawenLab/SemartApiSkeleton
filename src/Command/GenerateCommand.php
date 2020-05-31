<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Command;

use Alpabit\ApiSkeleton\Generator\GeneratorFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class GenerateCommand extends Command
{
    private const NAMESPACE = 'Alpabit\ApiSkeleton\Entity';

    private $generatorFactory;

    public function __construct(GeneratorFactory $generatorFactory)
    {
        $this->generatorFactory = $generatorFactory;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('semart:crud:generate')
            ->setAliases(['semart:generate', 'semart:gen'])
            ->setDescription('Generate RESTful API')
            ->setDescription('Generate RESTful API')
            ->addArgument('entity', InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $entity */
        $entity = $input->getArgument('entity');
        $reflection = new \ReflectionClass(sprintf('%s\%s', self::NAMESPACE, $entity));
        $application = $this->getApplication();

        $output->writeln('<info>Creating Migration File</info>');
        $migrate = $application->find('doctrine:migration:diff');
        $migrate->ignoreValidationErrors();
        $migrate->run(new ArrayInput([
            'command' => 'doctrine:migration:diff',
            '--allow-empty-diff' => null,
            '--no-interaction' => null,
        ]), $output);

        $output->writeln('<info>Running Semart Schema Updater</info>');
        $update = $application->find('doctrine:migration:migrate');
        $update->run(new ArrayInput([
            'command' => 'doctrine:migration:migrate',
            '--no-interaction' => null,
        ]), $output);

        $output->writeln('<info>Running Semart RESTful API Generator</info>');
        $this->generatorFactory->generate($reflection);

        $output->writeln(sprintf('<comment>Simple RESTful for %s class is generated</comment>', $reflection->getName()));

        return 0;
    }
}
