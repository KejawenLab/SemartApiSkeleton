<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Command;

use KejawenLab\Semart\ApiSkeleton\Generator\GeneratorFactory;
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
    private const NAMESPACE = 'KejawenLab\Semart\ApiSkeleton\Entity';

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
        $updatee = $application->find('doctrine:migration:diff');
        $updatee->run(new ArrayInput(['command' => 'doctrine:migration:diff']), $output);

        $output->writeln('<info>Running Semart Schema Updater</info>');
        $updatee = $application->find('doctrine:schema:update');
        $updatee->run(new ArrayInput([
            'command' => 'doctrine:schema:update',
            '--force' => true,
            '--no-interaction' => true,
        ]), $output);

        $output->writeln('<info>Running Semart RESTful API Generator</info>');
        $this->generatorFactory->generate($reflection);

        $output->writeln(sprintf('<comment>Simple RESTful for %s class is generated</comment>', $reflection->getName()));

        return 0;
    }
}
