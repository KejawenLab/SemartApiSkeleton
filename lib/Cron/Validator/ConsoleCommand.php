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
#[Attribute(Attribute::TARGET_CLASS)]
final class ConsoleCommand extends Constraint
{
    public function getMessage(): string
    {
        return 'sas.validator.cron.command_not_found';
    }

    #[\Override]
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
