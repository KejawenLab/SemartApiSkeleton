<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class LoginController extends AbstractController
{
    public const ROUTE_NAME = 'admin_login';

    /**
     * @Route("/login", name=LoginController::ROUTE_NAME, methods={"GET", "POST"})
     */
    public function __invoke(Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('admin_home');
        }

        return $this->render('layout/login.html.twig');
    }
}
