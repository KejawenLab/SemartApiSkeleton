<?php

declare(strict_types=1);

namespace App\Controller;

use App\Security\Model\UserInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
class SecurityController extends AbstractFOSRestController
{
    /**
     * @Route("/login", methods={"POST"})
     */
    public function check()
    {
        /** @var UserInterface $user */
        $user = $this->getUser();

        return $this->view([
            'fullname' => $user->getFullName(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
        ]);
    }
}
