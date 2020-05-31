<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Generator\Model;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface GeneratorInterface
{
    public function generate(\ReflectionClass $class, OutputInterface $output): void;
}
