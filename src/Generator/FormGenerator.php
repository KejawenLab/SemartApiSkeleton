<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Generator;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class FormGenerator extends AbstractGenerator
{
    public function generate(\ReflectionClass $class, OutputInterface $output): void
    {
        $shortName = $class->getShortName();
        $console = new Application($this->kernel);

        $formGenerator = $console->find('make:form');
        $formGenerator->run(new ArrayInput([
            'command' => 'make:form',
            'name' => sprintf('%sType', $shortName),
            'bound-class' => $class->getName(),
            '--no-interaction' => null,
        ]), $output);
    }
}
