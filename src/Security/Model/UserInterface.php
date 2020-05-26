<?php

declare(strict_types=1);

namespace App\Security\Model;

use Symfony\Component\Security\Core\User\UserInterface as SymfonyUser;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
interface UserInterface extends SymfonyUser
{
    public function getId(): string;

    public function getSupervisor(): self;

    public function getFullName();

    public function getEmail();

    public function getGroup(): GroupInterface;
}
