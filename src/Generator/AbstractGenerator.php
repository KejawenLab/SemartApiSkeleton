<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Generator;

use KejawenLab\Semart\ApiSkeleton\Generator\Model\GeneratorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
abstract class AbstractGenerator implements GeneratorInterface
{
    public function getPriority(): int
    {
        return static::DEFAULT_PRIORITY;
    }
}
