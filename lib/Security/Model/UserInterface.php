<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Model;

use KejawenLab\ApiSkeleton\Entity\EntityInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface UserInterface extends AuthInterface, EntityInterface
{
    public function getSupervisor(): ?self;

    public function getProfileImage(): ?string;

    public function getFullName(): ?string;

    public function getUsername(): ?string;

    public function getEmail(): ?string;

    public function setPlainPassword(string $plainPassword): void;

    public function getPlainPassword(): ?string;

    public function getPassword(): ?string;

    public function getDeviceId(): ?string;

    public function setDeviceId(string $deviceId): void;
}
