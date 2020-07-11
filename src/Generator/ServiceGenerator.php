<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Generator;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class ServiceGenerator extends AbstractGenerator
{
    public function generate(\ReflectionClass $class, OutputInterface $output): void
    {
        $shortName = $class->getShortName();
        $template = $this->twig->render('generator/service.php.twig', ['entity' => $shortName]);

        $output->writeln(sprintf('<comment>Generating class <info>"Alpabit\ApiSkeleton\%s\%sService"</info></comment>', $shortName, $shortName));
        $this->fileSystem->dumpFile(sprintf('%s/src/%s/%sService.php', $this->kernel->getProjectDir(), $shortName, $shortName), $template);
    }
}
