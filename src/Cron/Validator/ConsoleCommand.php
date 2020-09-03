<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cron\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class ConsoleCommand extends Constraint
{
    public function getMessage(): string
    {
        return 'sas.validator.command.not_found';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
