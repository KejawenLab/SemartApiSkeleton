<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Generator;

use KejawenLab\Semart\ApiSkeleton\Generator\Model\GeneratorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class GeneratorFactory
{
    /**
     * @var GeneratorInterface[]
     */
    private $generators = [];

    public function __construct(array $generators = [])
    {
        foreach ($generators as $generator) {
            $this->addGenerator($generator);
        }
    }

    public function generate(\ReflectionClass $class): void
    {
        foreach ($this->generators as $generator) {
            $generator->generate($class);
        }
    }

    private function addGenerator(GeneratorInterface $generator)
    {
        $index = 255 === $generator->getPriority() ?: \count($this->generators);

        $this->generators[$index] = $generator;
    }
}
