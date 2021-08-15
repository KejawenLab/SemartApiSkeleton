<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security;

use KejawenLab\ApiSkeleton\Security\Model\AuthInterface;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    private ?GroupInterface $group;

    private string $id;

    private ?string $profileImage;

    private string $username;

    private string $password;

    private string $class;

    public function __construct(?AuthInterface $user = null)
    {
        if ($user) {
            $this->id = (string) $user->getRecordId();
            $this->group = $user->getGroup();
            $this->username = (string) $user->getIdentity();
            $this->password = (string) $user->getCredential();
            $this->class = $user::class;

            if (method_exists($user, 'getProfileImage')) {
                $this->profileImage = $user->getProfileImage();
            }
        }
    }

    public function getGroup(): ?GroupInterface
    {
        return $this->group;
    }

    public function getProfileImage(): ?string
    {
        return $this->profileImage;
    }

    public function setProfileImage(string $profileImage): void
    {
        $this->profileImage = $profileImage;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }
}
