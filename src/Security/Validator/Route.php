<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Security\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Route extends Constraint
{
    public function getMessage(): string
    {
        return 'Route "[ROUTE]" ia not found';
    }

    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
