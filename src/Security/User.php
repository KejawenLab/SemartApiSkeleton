<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security;

use Alpabit\ApiSkeleton\Security\Model\AuthInterface;
use Alpabit\ApiSkeleton\Security\Model\GroupInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class User implements UserInterface
{
    private GroupInterface $group;

    private string $username;

    private string $password;

    private string $class;

    public function __construct(AuthInterface $user = null)
    {
        if ($user) {
            $this->group = $user->getGroup();
            $this->username = $user->getIdentity();
            $this->password = $user->getCredential();
            $this->class = get_class($user);
        }
    }

    public function getGroup(): GroupInterface
    {
        return $this->group;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }
}
