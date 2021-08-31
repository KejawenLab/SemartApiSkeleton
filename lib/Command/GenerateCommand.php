<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Command;

use Exception;
use KejawenLab\ApiSkeleton\Generator\GeneratorFactory;
use KejawenLab\ApiSkeleton\Generator\Model\GeneratorInterface;
use KejawenLab\ApiSkeleton\Security\Service\MenuService;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class GenerateCommand extends Command
{
    private const NAMESPACE = 'KejawenLab\\Application\\Entity';

    public function __construct(private GeneratorFactory $generator, private MenuService $menuService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('semart:generate')
            ->setDescription('Generate RESTful API and Admin Page')
            ->addArgument('entity', InputArgument::REQUIRED)
            ->addOption('parent', 'p', InputOption::VALUE_REQUIRED)
            ->addOption('admin', 'admin', InputOption::VALUE_NONE)
            ->addOption('api', 'api', InputOption::VALUE_NONE)
            ->addOption('folder', 'folder', InputOption::VALUE_NONE)
        ;
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->writeln('<fg=green;options=bold>
   _____                           __     ______                           __
  / ___/___  ____ ___  ____ ______/ /_   / ____/__  ____  ___  _________ _/ /_____  _____
  \__ \/ _ \/ __ `__ \/ __ `/ ___/ __/  / / __/ _ \/ __ \/ _ \/ ___/ __ `/ __/ __ \/ ___/
 ___/ /  __/ / / / / / /_/ / /  / /_   / /_/ /  __/ / / /  __/ /  / /_/ / /_/ /_/ / /
/____/\___/_/ /_/ /_/\__,_/_/   \__/   \____/\___/_/ /_/\___/_/   \__,_/\__/\____/_/

By: KejawenLab - Muhamad Surya Iksanudin<<comment>surya.iksanudin@gmail.com</comment>>
</>');
        $io->newLine();

        /** @var string $entity */
        $entity = $input->getArgument('entity');
        $class = sprintf('%s\%s', self::NAMESPACE, $entity);

        $scope = GeneratorInterface::SCOPE_ALL;
        if ($input->getOption('admin')) {
            $scope = GeneratorInterface::SCOPE_ADMIN;
        }

        if ($input->getOption('api')) {
            $scope = GeneratorInterface::SCOPE_API;
        }

        $reflection = new ReflectionClass($class);
        $application = $this->getApplication();

        $io->title('Running Migration');

        $input = new ArrayInput(['command' => 'doctrine:migrations:diff']);
        $input->setInteractive(false);
        $migration = $application->find('doctrine:migrations:diff');
        $migration->run($input, $output);

        $input = new ArrayInput(['command' => 'doctrine:migrations:migrate']);
        $input->setInteractive(false);
        $migration = $application->find('doctrine:migrations:migrate');
        $migration->run($input, $output);

        $io->title(sprintf('Generate classes for <info>%s</info>', $class));
        $this->generator->generate($reflection, $scope, $output, $input->getOption('folder') ?: '');

        if ($parentCode = $input->getOption('parent')) {
            $io->comment(sprintf('Apply parent menu to %s', $parentCode));
            $menu = $this->menuService->getMenuByCode($reflection->getShortName());
            $parent = $this->menuService->getMenuByCode($parentCode);
            if ($menu && $parent) {
                $menu->setParent($parent);

                $this->menuService->save($menu);
            }
        }

        $io->title('Clear Cache');
        $update = $application->find('cache:clear');
        $update->run(new ArrayInput([
            'command' => 'cache:clear',
        ]), $output);

        $io->success('Restart your container to apply changes');

        return 0;
    }
}
