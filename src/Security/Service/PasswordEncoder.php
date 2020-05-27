<?php

declare(strict_types=1);

namespace App\Security\Service;

use App\Security\Model\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class PasswordEncoder
{
    private $service;

    public function __construct(UserPasswordEncoderInterface $service)
    {
        $this->service = $service;
    }

    public function encode(UserInterface $user)
    {
        if ($plainPassword = $user->getPlainPassword()) {
            $user->setPassword($this->service->encodePassword($user, $plainPassword));
        }
    }
}
