<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller;

use KejawenLab\ApiSkeleton\Admin\AdminContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class LoginController extends AbstractController
{
    public function __construct(private AuthenticationUtils $authenticationUtils)
    {
    }

    /**
     * @Route("/login", name=AdminContext::LOGIN_ROUTE, methods={"GET", "POST"})
     */
    public function __invoke(): Response
    {
        return $this->render('layout/login.html.twig', [
            'error' => $this->authenticationUtils->getLastAuthenticationError(),
            'username' => $this->authenticationUtils->getLastUsername(),
        ]);
    }
}
