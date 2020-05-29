<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Generator;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class SearchQueryExtensionGenerator extends AbstractGenerator
{
    public function generate(\ReflectionClass $entityClass): void
    {
        $shortName = $entityClass->getShortName();
        $template = $this->twig->render('generator/search_query.php.twig', ['entity' => $shortName]);

        $this->fileSystem->dumpFile(sprintf('%s/src/%s/Query/SearchQueryExtension.php', $this->kernel->getProjectDir(), $shortName), $template);
    }
}
