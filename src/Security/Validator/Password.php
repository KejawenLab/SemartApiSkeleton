<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Password extends Constraint
{
    public function getMessage(): string
    {
        return 'Password not match';
    }
}
