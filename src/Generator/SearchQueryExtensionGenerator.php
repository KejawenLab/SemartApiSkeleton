<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class SearchQueryExtensionGenerator extends AbstractGenerator
{
    public function generate(\ReflectionClass $class, OutputInterface $output): void
    {
        $shortName = $class->getShortName();
        $queryFile = sprintf('%s/src/%s/Query/SearchQueryExtension.php', $this->kernel->getProjectDir(), $shortName);
        if (!$this->fileSystem->exists($queryFile)) {
            $template = $this->twig->render('generator/search_query.php.twig', ['entity' => $shortName]);
            $output->writeln(sprintf('<comment>Generating class <info>"KejawenLab\ApiSkeleton\%s\Query\SearchQueryExtension"</info></comment>', $shortName));
            $this->fileSystem->dumpFile($queryFile, $template);
        } else {
            $output->write(sprintf('<warning>File "%s" is exists. Skipped</warning>', $queryFile));
        }
    }

    public function support(string $scope): bool
    {
        return static::SCOPE_API === $scope;
    }
}
