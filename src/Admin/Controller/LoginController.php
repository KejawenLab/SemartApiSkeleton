<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller;

use KejawenLab\ApiSkeleton\Admin\AdminContext;
use KejawenLab\ApiSkeleton\Util\Encryptor;
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
        $session = $request->getSession();
        $session->set(AdminContext::ADMIN_CRSF_SESSION_KEY, Encryptor::hash(date('YmdHis')));

        return $this->render('layout/login.html.twig', [
            'sas_crsf_token_key' => AdminContext::ADMIN_CRSF_REQUEST_KEY,
            'sas_crsf_token_value' => $session->get(AdminContext::ADMIN_CRSF_SESSION_KEY),
        ]);
    }
}
