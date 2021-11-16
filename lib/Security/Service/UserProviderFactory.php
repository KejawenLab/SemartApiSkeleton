<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Service;

use KejawenLab\ApiSkeleton\Security\Model\AuthInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserProviderInterface as Provider;
use KejawenLab\ApiSkeleton\Security\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class UserProviderFactory implements UserProviderInterface
{
    /**
     * @param Provider[] $providers
     */
    public function __construct(
        private iterable $providers,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function loadUserByUsername(string $username): User
    {
        return $this->loadUserByIdentifier($username);
    }

    public function getRealUser(User $user): ?AuthInterface
    {
        foreach ($this->providers as $provider) {
            if ($provider->support($user->getClass())) {
                return $provider->findByIdentifier($user->getUserIdentifier());
            }
        }

        throw new UserNotFoundException();
    }

    public function refreshUser(UserInterface $user): User
    {
        return $this->loadUserByUsername($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }

    public function loadUserByIdentifier(string $identifier): User
    {
        foreach ($this->providers as $provider) {
            $user = $provider->findByIdentifier($identifier);
            if (!$user instanceof AuthInterface) {
                continue;
            }

            $authUser = new User($user);
            if (!$user->isEncoded()) {
                $authUser->setPassword($this->passwordHasher->hashPassword($authUser, $authUser->getPassword()));
            }

            return $authUser;
        }

        throw new UserNotFoundException();
    }
}
