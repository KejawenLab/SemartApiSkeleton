<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Generator\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface GeneratorInterface
{
    public function generate(\ReflectionClass $entityClass): void;
}
