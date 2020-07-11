<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Model;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
interface UserInterface
{
    public function getSupervisor(): ?self;

    public function getFullName(): ?string;

    public function getEmail(): ?string;

    public function getGroup(): ?GroupInterface;

    public function getPlainPassword(): ?string;
}
