<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cron\Validator;

use Cron\Exception\InvalidPatternException;
use Cron\Validator\CrontabValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class CronScheduleFormatValidator extends ConstraintValidator
{
    private CrontabValidator $validator;

    public function __construct(CrontabValidator $validator)
    {
        $this->validator = $validator;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof CronScheduleFormat) {
            throw new UnexpectedTypeException($constraint, CronScheduleFormat::class);
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        try {
            $this->validator->validate($value);
        } catch (InvalidPatternException $exception) {
            $this->context->buildViolation($exception->getMessage())->addViolation();
        }
    }
}
