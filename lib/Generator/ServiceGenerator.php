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
final class ServiceGenerator extends AbstractGenerator
{
    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function generate(ReflectionClass $class, OutputInterface $output, ?string $folder = null): void
    {
        $shortName = $class->getShortName();

        $serviceFile = sprintf('%s/app/%s/%sService.php', $this->kernel->getProjectDir(), ($folder ?: $shortName), $shortName);
        if (!$this->fileSystem->exists($serviceFile)) {
            $template = $this->twig->render('generator/service.php.twig', ['entity' => $shortName]);
            $output->writeln(sprintf(
                '<comment>Generating class <info>"KejawenLab\\Application\\%s\\%sService"</info></comment>',
                ($folder ?: $shortName),
                $shortName
            ));
            $this->fileSystem->dumpFile($serviceFile, $template);
        } else {
            $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $serviceFile));
        }
    }

    public function support(string $scope): bool
    {
        return self::SCOPE_API === $scope;
    }
}
