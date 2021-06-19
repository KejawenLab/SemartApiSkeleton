<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Service;

use KejawenLab\ApiSkeleton\Security\Model\AuthInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserProviderInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserRepositoryInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class UserProvider implements UserProviderInterface
{
    public function __construct(private UserRepositoryInterface $repository, private string $class)
    {
    }

    public function findByIdentifier(string $identifier): ?AuthInterface
    {
        return $this->repository->findByUsername($identifier);
    }

    public function support(string $class): bool
    {
        return $this->class === $class;
    }
}
