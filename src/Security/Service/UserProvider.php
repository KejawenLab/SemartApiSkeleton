<?php

declare(strict_types=1);

namespace App\Security\Service;

use App\Security\Model\UserInterface;
use App\Security\Model\UserProviderInterface;
use App\Security\Model\UserRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
class UserProvider implements UserProviderInterface
{
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function findUsername(string $username): ?UserInterface
    {
        return $this->repository->findByUsername($username);
    }

    public function support(string $class): bool
    {
        return UserInterface::class === $class;
    }
}
