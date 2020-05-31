<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Generator;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class RepositoryGenerator extends AbstractGenerator
{
    public function generate(\ReflectionClass $entityClass): void
    {
        $shortName = $entityClass->getShortName();
        $repository = $this->twig->render('generator/repository.php.twig', ['entity' => $shortName]);
        $repositoryModel = $this->twig->render('generator/repository_model.php.twig', ['entity' => $shortName]);

        $this->fileSystem->dumpFile(sprintf('%s/src/Repository/%sRepository.php', $this->kernel->getProjectDir(), $shortName), $repository);
        $this->fileSystem->dumpFile(sprintf('%s/src/%s/Model/%sRepositoryInterface.php', $this->kernel->getProjectDir(), $shortName, $shortName), $repositoryModel);
    }
}
