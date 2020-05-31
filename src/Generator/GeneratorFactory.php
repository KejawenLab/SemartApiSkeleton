<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Generator;

use Alpabit\ApiSkeleton\Generator\Model\GeneratorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class GeneratorFactory
{
    /**
     * @var GeneratorInterface[]
     */
    private $generators = [];

    public function __construct(iterable $generators)
    {
        $this->generators = $generators;
    }

    public function generate(\ReflectionClass $class): void
    {
        foreach ($this->generators as $generator) {
            $generator->generate($class);
        }
    }
}
