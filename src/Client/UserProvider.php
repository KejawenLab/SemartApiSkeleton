<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Client;

use Alpabit\ApiSkeleton\Client\Model\ClientRepositoryInterface;
use Alpabit\ApiSkeleton\Security\Model\AuthInterface;
use Alpabit\ApiSkeleton\Security\Model\UserProviderInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class UserProvider implements UserProviderInterface
{
    private string $class;

    private ClientRepositoryInterface $repository;

    public function __construct(string $class, ClientRepositoryInterface $repository)
    {
        $this->class = $class;
        $this->repository = $repository;
    }

    public function findUsername(string $apiKey): ?AuthInterface
    {
        return $this->repository->findByApiKey($apiKey);
    }

    public function support(string $class): bool
    {
        return $this->class === $class;
    }
}
