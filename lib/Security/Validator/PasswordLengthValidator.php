<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class PasswordLengthValidator extends ConstraintValidator
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof PasswordLength) {
            throw new UnexpectedTypeException($constraint, PasswordLength::class);
        }

        if (!$value) {
            return;
        }

        if (6 >= \strlen($value)) {
            $this->context->buildViolation($this->translator->trans($constraint->getMessage(), [], 'validators'))->addViolation();
        }
    }
}
