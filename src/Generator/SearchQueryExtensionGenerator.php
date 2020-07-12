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
        $template = $this->twig->render('generator/search_query.php.twig', ['entity' => $shortName]);

        $output->writeln(sprintf('<comment>Generating class <info>"KejawenLab\ApiSkeleton\%s\Query\SearchQueryExtension"</info></comment>', $shortName));
        $this->fileSystem->dumpFile(sprintf('%s/src/%s/Query/SearchQueryExtension.php', $this->kernel->getProjectDir(), $shortName), $template);
    }
}
