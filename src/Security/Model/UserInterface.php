<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
interface UserInterface extends AuthInterface
{
    public function getSupervisor(): ?self;

    public function getFullName(): ?string;

    public function getUsername(): ?string;

    public function getEmail(): ?string;

    public function getPlainPassword(): ?string;

    public function getPassword(): ?string;
}
