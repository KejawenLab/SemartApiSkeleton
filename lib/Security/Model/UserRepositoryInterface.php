<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Model;

use KejawenLab\ApiSkeleton\Pagination\Model\PaginatableRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface UserRepositoryInterface extends PaginatableRepositoryInterface
{
    public function isSupervisor(UserInterface $user, UserInterface $supervisor): bool;

    public function findByUsername(string $username): ?UserInterface;
}
