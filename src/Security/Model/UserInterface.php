<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Security\Model;

use Symfony\Component\Security\Core\User\UserInterface as SymfonyUser;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface UserInterface extends SymfonyUser
{
    public function getSupervisor(): ?self;

    public function getFullName(): ?string;

    public function getEmail(): ?string;

    public function getGroup(): ?GroupInterface;

    public function getPlainPassword(): ?string;
}
