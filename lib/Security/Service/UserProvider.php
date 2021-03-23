<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Service;

use KejawenLab\ApiSkeleton\Security\Model\AuthInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserProviderInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class UserProvider implements UserProviderInterface
{
    private UserRepositoryInterface $repository;

    private string $class;

    public function __construct(UserRepositoryInterface $repository, string $class)
    {
        $this->class = $class;
        $this->repository = $repository;
    }

    public function findUsername(string $username): ?AuthInterface
    {
        return $this->repository->findByUsername($username);
    }

    public function support(string $class): bool
    {
        return $this->class === $class;
    }
}
