<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Security\Service;

use KejawenLab\Semart\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\Semart\ApiSkeleton\Security\Model\UserProviderInterface;
use KejawenLab\Semart\ApiSkeleton\Security\Model\UserRepositoryInterface;

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
