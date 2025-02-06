<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation()
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Route extends Constraint
{
    public function getMessage(): string
    {
        return 'validation.route.not_found';
    }

    #[\Override]
    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
