<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Message;

use KejawenLab\ApiSkeleton\Security\User;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class PasswordHistory
{
    public function __construct(private readonly User $user, private readonly string $password)
    {
    }

    public function getSource(): string
    {
        return $this->user->getClass();
    }

    public function getIdentifier(): string
    {
        return $this->user->getId();
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
