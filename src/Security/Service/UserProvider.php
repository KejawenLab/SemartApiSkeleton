<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Service;

use Alpabit\ApiSkeleton\Security\Model\UserInterface;
use Alpabit\ApiSkeleton\Security\Model\UserProviderInterface;
use Alpabit\ApiSkeleton\Security\Model\UserRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class UserProvider implements UserProviderInterface
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
