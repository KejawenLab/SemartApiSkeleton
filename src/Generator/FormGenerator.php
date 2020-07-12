<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class FormGenerator extends AbstractGenerator
{
    public function generate(\ReflectionClass $class, OutputInterface $output): void
    {
        $shortName = $class->getShortName();
        $properties = $class->getProperties(\ReflectionProperty::IS_PRIVATE);

        $template = $this->twig->render('generator/form.php.twig', ['entity' => $shortName, 'properties' => $properties]);

        $output->writeln(sprintf('<comment>Generating class <info>"KejawenLab\ApiSkeleton\Form\%sType"</info></comment>', $shortName));
        $this->fileSystem->dumpFile(sprintf('%s/src/Form/%sType.php', $this->kernel->getProjectDir(), $shortName), $template);
    }
}
