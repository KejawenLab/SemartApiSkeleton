<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class PasswordLengthValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof PasswordLength) {
            throw new UnexpectedTypeException($constraint, PasswordLength::class);
        }

        if (!$value) {
            return;
        }

        if (6 >= $value) {
            $this->context->buildViolation($constraint->getMessage())->addViolation();
        }
    }
}
