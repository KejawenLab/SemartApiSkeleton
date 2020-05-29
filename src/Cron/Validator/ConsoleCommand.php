<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Cron\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class ConsoleCommand extends Constraint
{
    public function getMessage(): string
    {
        return '[COMMAND] is not valid';
    }

    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
