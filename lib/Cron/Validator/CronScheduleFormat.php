<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cron\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class CronScheduleFormat extends Constraint
{
    public function getMessage(): string
    {
        return 'sas.validator.cron.format_not_valid';
    }
}
