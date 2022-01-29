<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cron\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation()
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class CronScheduleFormat extends Constraint
{
    public function getMessage(): string
    {
        return 'sas.validator.cron.format_not_valid';
    }

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
