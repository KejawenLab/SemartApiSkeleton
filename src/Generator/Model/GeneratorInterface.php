<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Generator\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface GeneratorInterface
{
    public function generate(\ReflectionClass $entityClass): void;
}
