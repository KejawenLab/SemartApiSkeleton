<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Route extends Constraint
{
    public function getMessage(): string
    {
        return 'Route "[ROUTE]" ia not found';
    }

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
