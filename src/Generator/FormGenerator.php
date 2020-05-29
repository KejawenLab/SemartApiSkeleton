<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Generator;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class FormGenerator extends AbstractGenerator
{
    public function generate(\ReflectionClass $entityClass): void
    {
        $shortName = $entityClass->getShortName();
        $template = $this->twig->render('generator/form.php.twig', ['entity' => $shortName]);

        $this->fileSystem->dumpFile(sprintf('%s/src/Form/Type/%sType.php', $this->kernel->getProjectDir(), $shortName), $template);
    }
}
