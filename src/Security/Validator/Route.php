<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Route extends Constraint
{
    public function getMessage(): string
    {
        return 'Route "[ROUTE]" ia not found';
    }

    public function getTargets()
    {
        return static::PROPERTY_CONSTRAINT;
    }
}
