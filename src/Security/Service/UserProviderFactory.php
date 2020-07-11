<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Service;

use Alpabit\ApiSkeleton\Security\Model\UserInterface as Model;
use Alpabit\ApiSkeleton\Security\Model\UserProviderInterface as Provider;
use Alpabit\ApiSkeleton\Security\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class UserProviderFactory implements UserProviderInterface
{
    /**
     * @var Provider[]
     */
    private iterable $providers;

    public function __construct(iterable $providers)
    {
        $this->providers = $providers;
    }

    public function loadUserByUsername(string $username): User
    {
        foreach ($this->providers as $provider) {
            if ($user = $provider->findUsername($username)) {
                return new User($user);
            }
        }

        throw new UsernameNotFoundException();
    }

    public function getRealUser(User $user): Model
    {
        foreach ($this->providers as $provider) {
            if ($provider->support($user->getClass())) {
                return $provider->findUsername($user->getUsername());
            }
        }

        throw new UsernameNotFoundException();
    }

    public function refreshUser(UserInterface $user): User
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass(string $class): bool
    {
        foreach ($this->providers as $provider) {
            if ($provider->support($class)) {
                return true;
            }
        }

        return false;
    }
}
