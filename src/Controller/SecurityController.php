<?php

declare(strict_types=1);

namespace App\Controller;

use App\Security\Model\UserInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\RuntimeException;

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
        throw new RuntimeException('Invalid security configuration');
    }
}
