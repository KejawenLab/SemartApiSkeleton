<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class LoginController extends AbstractController
{
    public const ROUTE_NAME = 'admin_login';

    /**
     * @Route("/login", name=LoginController::ROUTE_NAME, methods={"GET", "POST"})
     */
    public function __invoke(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('admin_home');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('layout/login.html.twig', [
            'error' => $error,
            'username' => $lastUsername,
        ]);
    }
}
