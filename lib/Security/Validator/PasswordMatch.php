<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class PasswordMatch extends Constraint
{
    public function getMessage(): string
    {
        return 'sas.validator.password.not_match';
    }
}
