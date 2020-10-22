<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class ServiceGenerator extends AbstractGenerator
{
    public function generate(\ReflectionClass $class, OutputInterface $output, ?string $folder = null): void
    {
        $shortName = $class->getShortName();

        $serviceFile = sprintf('%s/src/%s/%sService.php', $this->kernel->getProjectDir(), ($folder? $folder: $shortName), $shortName);
        if (!$this->fileSystem->exists($serviceFile)) {
            $template = $this->twig->render('generator/service.php.twig', ['entity' => $shortName]);
            $output->writeln(sprintf('<comment>Generating class <info>"KejawenLab\ApiSkeleton\%s\%sService"</info></comment>', ($folder? $folder: $shortName), $shortName));
            $this->fileSystem->dumpFile($serviceFile, $template);
        } else {
            $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $serviceFile));
        }
    }

    public function support(string $scope): bool
    {
        return static::SCOPE_API === $scope;
    }
}
