<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class PasswordHistory extends Constraint
{
    public function getMessage(): string
    {
        return 'sas.validator.password.already_used';
    }
}
