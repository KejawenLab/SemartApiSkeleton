<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Command;

use KejawenLab\ApiSkeleton\Generator\GeneratorFactory;
use KejawenLab\ApiSkeleton\Security\Service\MenuService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class GenerateCommand extends Command
{
    private const NAMESPACE = 'KejawenLab\ApiSkeleton\Entity';

    private GeneratorFactory $generator;

    private MenuService $menuService;

    public function __construct(GeneratorFactory $generator, MenuService $menuService)
    {
        $this->generator = $generator;
        $this->menuService = $menuService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('semart:generate')
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

By: KejawenLab - Muhamad Surya Iksanudin<<comment>surya.kejawen@gmail.com</comment>>

</>');
        if (!$input->getOption('force')) {
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion('<comment>[!!!WARNING!!!]</comment><question> Semart Api Generator will overwrite your file if it exists. Continue?</question> (y/n)', false);
            if (!$helper->ask($input, $output, $question)) {
                return 0;
            }
        }

        /** @var string $entity */
        $entity = $input->getArgument('entity');
        $reflection = new \ReflectionClass(sprintf('%s\%s', static::NAMESPACE, $entity));
        $application = $this->getApplication();

        $output->writeln('<info>Running Schema Updater</info>');
        $update = $application->find('doctrine:schema:update');
        $update->run(new ArrayInput([
            'command' => 'doctrine:schema:update',
            '--force' => null,
        ]), $output);

        $output->writeln('<info>Generating RESTful API</info>');
        $this->generator->generate($reflection, $output);

        if ($parentCode = $input->getOption('parent')) {
            $output->writeln(sprintf('<comment>Applying parent to menu</comment>'));
            $menu = $this->menuService->getMenuByCode($reflection->getShortName());
            $parent = $this->menuService->getMenuByCode($parentCode);
            if ($menu && $parent) {
                $menu->setParent($parent);

                $this->menuService->save($menu);
            }
        }

        $output->writeln(sprintf('<comment>RESTful API for class <info>"%s"</info> has been generated</comment>', $reflection->getName()));

        return 0;
    }
}
