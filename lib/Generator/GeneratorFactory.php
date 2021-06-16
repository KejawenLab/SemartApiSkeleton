<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator;

use KejawenLab\ApiSkeleton\Generator\Model\GeneratorInterface;
use ReflectionClass;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class GeneratorFactory
{
    public function __construct(
        /**
         * @var GeneratorInterface[]
         */
        private iterable $generators
    ) {
    }

    public function generate(ReflectionClass $class, string $scope, OutputInterface $output, ?string $folder = null): void
    {
        foreach ($this->generators as $generator) {
            if (GeneratorInterface::SCOPE_ALL !== $scope) {
                if ($generator->support($scope)) {
                    $generator->generate($class, $output, $folder);
                }
            } else {
                $generator->generate($class, $output, $folder);
            }
        }
    }
}
