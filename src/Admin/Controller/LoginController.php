<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class LoginController extends AbstractController
{
    /**
     * @Route("/login", methods={"GET"})
     */
    public function __invoke()
    {
        return $this->render('base.html.twig');
    }
}
