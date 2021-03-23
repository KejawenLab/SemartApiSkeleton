<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Service;

use KejawenLab\ApiSkeleton\Security\Model\AuthInterface;
use KejawenLab\ApiSkeleton\Security\Model\UserProviderInterface as Provider;
use KejawenLab\ApiSkeleton\Security\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(iterable $providers, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->providers = $providers;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function loadUserByUsername(string $username): User
    {
        foreach ($this->providers as $provider) {
            if ($user = $provider->findUsername($username)) {
                $authUser = new User($user);
                if (!$user->isEncoded()) {
                    $authUser->setPassword($this->passwordEncoder->encodePassword($authUser, $authUser->getPassword()));
                }

                return $authUser;
            }
        }

        throw new UsernameNotFoundException();
    }

    public function getRealUser(User $user): AuthInterface
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
        return User::class === $class;
    }
}
