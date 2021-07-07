<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator;

use ReflectionClass;
use Symfony\Component\Console\Output\OutputInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class RepositoryGenerator extends AbstractGenerator
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function generate(ReflectionClass $class, OutputInterface $output, ?string $folder = null): void
    {
        $shortName = $class->getShortName();
        $repositoryFile = sprintf('%s/app/Repository/%sRepository.php', $this->kernel->getProjectDir(), $shortName);
        $output->writeln(sprintf('<comment>Generating class <info>"KejawenLab\\Application\\Repository\\%sRepository"</info></comment>', $shortName));
        if (!$this->fileSystem->exists($repositoryFile)) {
            $repository = $this->twig->render('generator/repository.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($repositoryFile, $repository);
        } else {
            $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $repositoryFile));
        }

        $repositoryModelFile = sprintf('%s/app/%s/Model/%sRepositoryInterface.php', $this->kernel->getProjectDir(), ($folder ?: $shortName), $shortName);
        $output->writeln(sprintf(
            '<comment>Generating class <info>"KejawenLab\\Application\\%s\\Model\\%sRepositoryInterface"</info></comment>',
            ($folder ?: $shortName),
            $shortName
        ));
        if (!$this->fileSystem->exists($repositoryModelFile)) {
            $repositoryModel = $this->twig->render('generator/repository_model.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($repositoryModelFile, $repositoryModel);
        } else {
            $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $repositoryModelFile));
        }
    }

    public function support(string $scope): bool
    {
        return self::SCOPE_API === $scope;
    }
}
