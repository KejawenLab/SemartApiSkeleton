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
final class SearchQueryExtensionGenerator extends AbstractGenerator
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function generate(ReflectionClass $class, OutputInterface $output, ?string $folder = null): void
    {
        $shortName = $class->getShortName();
        $queryFile = sprintf('%s/app/%s/Query/%sQueryExtension.php', $this->kernel->getProjectDir(), ($folder ?: $shortName), $shortName);
        if (!$this->fileSystem->exists($queryFile)) {
            $template = $this->twig->render('generator/search_query.php.twig', ['entity' => $shortName]);
            $output->writeln(sprintf(
                '<comment>Generating class <info>"KejawenLab\\Application\\%s\\Query\\%sQueryExtension"</info></comment>',
                ($folder ?: $shortName),
                $shortName
            ));
            $this->fileSystem->dumpFile($queryFile, $template);
        } else {
            $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $queryFile));
        }
    }

    public function support(string $scope): bool
    {
        return self::SCOPE_API === $scope;
    }
}
