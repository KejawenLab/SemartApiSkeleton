<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Generator;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class RepositoryGenerator extends AbstractGenerator
{
    public function generate(\ReflectionClass $class, OutputInterface $output): void
    {
        $shortName = $class->getShortName();
        $repository = $this->twig->render('generator/repository.php.twig', ['entity' => $shortName]);
        $repositoryModel = $this->twig->render('generator/repository_model.php.twig', ['entity' => $shortName]);

        $output->writeln(sprintf('<comment>Generating class <info>"Alpabit\ApiSkeleton\Repository\%Repository"</info></comment>', $shortName));
        $this->fileSystem->dumpFile(sprintf('%s/src/Repository/%sRepository.php', $this->kernel->getProjectDir(), $shortName), $repository);
        $output->writeln(sprintf('<comment>Generating class <info>"Alpabit\ApiSkeleton\%s\Model\%sRepositoryInterface"</info></comment>', $shortName, $shortName));
        $this->fileSystem->dumpFile(sprintf('%s/src/%s/Model/%sRepositoryInterface.php', $this->kernel->getProjectDir(), $shortName, $shortName), $repositoryModel);
    }
}
