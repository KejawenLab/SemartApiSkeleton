<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cron\Validator;

use Cron\Exception\InvalidPatternException;
use Cron\Validator\CrontabValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class CronScheduleFormatValidator extends ConstraintValidator
{
    public function __construct(private readonly CrontabValidator $validator, private readonly TranslatorInterface $translator)
    {
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
        } catch (InvalidPatternException) {
            $this->context->buildViolation($this->translator->trans($constraint->getMessage(), [], 'validators'))->addViolation();
        }
    }
}
