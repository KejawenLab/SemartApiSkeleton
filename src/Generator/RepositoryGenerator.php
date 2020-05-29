<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class RepositoryGenerator extends AbstractGenerator
{
    private $twig;

    private $fileSystem;

    private $kernel;

    public function __construct(Environment $twig, Filesystem $fileSystem, KernelInterface $kernel)
    {
        $this->twig = $twig;
        $this->fileSystem = $fileSystem;
        $this->kernel = $kernel;
    }

    public function generate(\ReflectionClass $entityClass): void
    {
        $shortName = $entityClass->getShortName();
        $repository = $this->twig->render('generator/repository.php.twig', ['entity' => $shortName]);
        $repositoryModel = $this->twig->render('generator/repository_model.php.twig', ['entity' => $shortName]);

        $this->fileSystem->dumpFile(sprintf('%s/src/Repository/%sRepository.php', $this->kernel->getProjectDir(), $shortName), $repository);
        $this->fileSystem->dumpFile(sprintf('%s/src/%s/%sRepositoryInterface.php', $this->kernel->getProjectDir(), $shortName, $shortName), $repositoryModel);
    }

    public function getPriority(): int
    {
        return -255;
    }
}
