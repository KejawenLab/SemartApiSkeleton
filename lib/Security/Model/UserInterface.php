<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Security\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
interface UserInterface extends AuthInterface
{
    public function getSupervisor(): ?self;

    public function getFullName(): ?string;

    public function getUsername(): ?string;

    public function getEmail(): ?string;

    public function getPlainPassword(): ?string;

    public function getPassword(): ?string;

    public function getDeviceId(): ?string;

    public function setDeviceId(string $deviceId): void;
}
