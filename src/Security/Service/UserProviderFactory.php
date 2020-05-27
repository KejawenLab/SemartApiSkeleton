<?php

declare(strict_types=1);

namespace App\Security\Service;

use App\Security\Model\UserInterface as Model;
use App\Security\Model\UserProviderInterface as Provider;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class UserProviderFactory implements UserProviderInterface
{
    /**
     * @var Provider[]
     */
    private $providers;

    public function __construct(iterable $providers)
    {
        $this->providers = $providers;
    }

    public function loadUserByUsername(string $username): Model
    {
        foreach ($this->providers as $provider) {
            if ($user = $provider->findUsername($username)) {
                return $user;
            }
        }

        throw new UsernameNotFoundException();
    }

    public function refreshUser(UserInterface $user): Model
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
