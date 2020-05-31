<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Cron\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class ConsoleCommand extends Constraint
{
    public function getMessage(): string
    {
        return 'Command "[COMMAND]" is not found';
    }

    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
