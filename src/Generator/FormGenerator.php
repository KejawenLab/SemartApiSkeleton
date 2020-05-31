<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Generator;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class FormGenerator extends AbstractGenerator
{
    public function generate(\ReflectionClass $class, OutputInterface $output): void
    {
        $shortName = $class->getShortName();
        $template = $this->twig->render('generator/form.php.twig', ['entity' => $shortName]);

        $output->writeln(sprintf('<comment>Generating class <info>"Alpabit\ApiSkeleton\Form\Type\%sType"</info></comment>', $shortName));
        $this->fileSystem->dumpFile(sprintf('%s/src/Form/Type/%sType.php', $this->kernel->getProjectDir(), $shortName), $template);
    }
}
