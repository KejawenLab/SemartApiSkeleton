<?php

declare(strict_types=1);

namespace App\Security\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface UserRepositoryInterface
{
    public function isSupervisor(UserInterface $user, UserInterface $supervisor): bool;

    public function findByUsername(string $username): ?UserInterface;
}
