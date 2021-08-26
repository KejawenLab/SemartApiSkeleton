<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Model;

use DateTimeImmutable;
use KejawenLab\ApiSkeleton\Entity\EntityInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface UserInterface extends AuthInterface, EntityInterface
{
    public const PROFILE_MEDIA_FOLDER = 'profiles';

    public function getSupervisor(): ?self;

    public function getProfileImage(): ?string;

    public function setProfileImage(string $profileImage): void;

    public function getFullName(): ?string;

    public function getUsername(): ?string;

    public function getEmail(): ?string;

    public function setPlainPassword(string $plainPassword): void;

    public function getPlainPassword(): ?string;

    public function getPassword(): ?string;

    public function getDeviceId(): ?string;

    public function setDeviceId(string $deviceId): void;

    public function getLastLogin(): DateTimeImmutable;

    public function setLastLogin(DateTimeImmutable $lastLogin): void;

    public function getFile(): ?File;
}
