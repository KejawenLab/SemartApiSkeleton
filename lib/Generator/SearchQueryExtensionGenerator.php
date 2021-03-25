<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class SearchQueryExtensionGenerator extends AbstractGenerator
{
    public function generate(\ReflectionClass $class, OutputInterface $output, ?string $folder = null): void
    {
        $shortName = $class->getShortName();
        $queryFile = sprintf('%s/app/%s/Query/%sQueryExtension.php', $this->kernel->getProjectDir(), ($folder ? $folder : $shortName), $shortName);
        if (!$this->fileSystem->exists($queryFile)) {
            $template = $this->twig->render('generator/search_query.php.twig', ['entity' => $shortName]);
            $output->writeln(sprintf('<comment>Generating class <info>"KejawenLab\\ApiSkeleton\\Application\\%s\\Query\\%sQueryExtension"</info></comment>', ($folder ? $folder : $shortName), $shortName));
            $this->fileSystem->dumpFile($queryFile, $template);
        } else {
            $output->writeln(sprintf('<info>File "%s" is exists. Skipped</info>', $queryFile));
        }
    }

    public function support(string $scope): bool
    {
        return static::SCOPE_API === $scope;
    }
}
