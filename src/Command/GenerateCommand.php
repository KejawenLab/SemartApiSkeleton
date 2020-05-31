<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Command;

use Alpabit\ApiSkeleton\Generator\GeneratorFactory;
use Alpabit\ApiSkeleton\Security\Service\MenuService;
use Alpabit\ApiSkeleton\Util\StringUtil;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class GenerateCommand extends Command
{
    private const NAMESPACE = 'Alpabit\ApiSkeleton\Entity';

    private $generatorFactory;

    private $menuService;

    public function __construct(GeneratorFactory $generatorFactory, MenuService $menuService)
    {
        $this->generatorFactory = $generatorFactory;
        $this->menuService = $menuService;

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
            ->addOption('parent', 'p', InputOption::VALUE_REQUIRED)
            ->addOption('force', 'f', InputOption::VALUE_NONE)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->write('<fg=green;options=bold>
   _____                           __     ______                           __
  / ___/___  ____ ___  ____ ______/ /_   / ____/__  ____  ___  _________ _/ /_____  _____
  \__ \/ _ \/ __ `__ \/ __ `/ ___/ __/  / / __/ _ \/ __ \/ _ \/ ___/ __ `/ __/ __ \/ ___/
 ___/ /  __/ / / / / / /_/ / /  / /_   / /_/ /  __/ / / /  __/ /  / /_/ / /_/ /_/ / /
/____/\___/_/ /_/ /_/\__,_/_/   \__/   \____/\___/_/ /_/\___/_/   \__,_/\__/\____/_/

<comment>By: KejawenLab - Muhamad Surya Iksanudin<surya.kejawen@gmail.com></comment>

</>');
        if (!$input->getOption('force')) {
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion('<comment>[!!!WARNING!!!]</comment><question> Semart Api Generator will override your file if it exists. Continue?</question> (y/n)', false);
            if (!$helper->ask($input, $output, $question)) {
                return 0;
            }
        }

        /** @var string $entity */
        $entity = $input->getArgument('entity');
        $reflection = new \ReflectionClass(sprintf('%s\%s', static::NAMESPACE, $entity));
        $application = $this->getApplication();

        $output->writeln('<info>Creating Migration File</info>');
        $migrate = $application->find('doctrine:migration:diff');
        $migrate->ignoreValidationErrors();
        $migrate->run(new ArrayInput([
            'command' => 'doctrine:migration:diff',
            '--allow-empty-diff' => null,
            '--no-interaction' => null,
        ]), $output);

        $output->writeln('<info>Running Schema Updater</info>');
        $update = $application->find('doctrine:migration:migrate');
        $update->run(new ArrayInput([
            'command' => 'doctrine:migration:migrate',
            '--no-interaction' => null,
        ]), $output);

        $output->writeln('<info>Generating RESTful API</info>');
        $this->generatorFactory->generate($reflection, $output);

        if ($parentCode = $input->getOption('parent')) {
            $output->writeln(sprintf('<comment>Applying parent to menu</comment>'));
            $menu = $this->menuService->getMenuByCode($reflection->getShortName());
            $parent = $this->menuService->getMenuByCode($parentCode);
            if ($menu && $parent) {
                $menu->setParent($parent);

                $this->menuService->save($menu);
            }
        }

        $output->writeln(sprintf('<comment>RESTful API for <info>"%s"</info> class is generated</comment>', $reflection->getName()));

        return 0;
    }
}
