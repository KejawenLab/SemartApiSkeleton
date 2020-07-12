<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class PasswordLength extends Constraint
{
    public function getMessage(): string
    {
        return 'Password length must longer than 6 characters';
    }
}
