<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="admin_login", methods={"GET", "POST"})
     */
    public function __invoke(Request $request)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('admin_home');
        }

        return $this->render('layout/login.html.twig');
    }
}
