<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\ApiClient;

use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientRepositoryInterface;
use KejawenLab\ApiSkeleton\Security\Model\AuthInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserProviderInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class UserProvider implements UserProviderInterface
{
    public function __construct(private readonly string $class, private readonly ApiClientRepositoryInterface $repository)
    {
    }

    public function findByIdentifier(string $identifier): ?AuthInterface
    {
        return $this->repository->findByApiKey($identifier);
    }

    public function support(string $class): bool
    {
        return $this->class === $class;
    }
}
