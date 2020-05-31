<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Cron\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class CronScheduleFormat extends Constraint
{
    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
