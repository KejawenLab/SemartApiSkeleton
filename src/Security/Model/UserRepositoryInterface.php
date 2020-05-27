<?php

declare(strict_types=1);

namespace App\Security\Model;

use App\Pagination\Model\PaginatableRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface UserRepositoryInterface extends PaginatableRepositoryInterface
{
    public function isSupervisor(UserInterface $user, UserInterface $supervisor): bool;

    public function findByUsername(string $username): ?UserInterface;
}
