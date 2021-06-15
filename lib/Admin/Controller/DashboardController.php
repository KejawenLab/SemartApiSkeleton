<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller;

use KejawenLab\ApiSkeleton\Admin\AdminContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class DashboardController extends AbstractController
{
    /**
     * @Route("/", name=AdminContext::ADMIN_ROUTE, methods={"GET"})
     */
    public function __invoke(): Response
    {
        return $this->render('dashboard/layout.html.twig', ['page_title' => 'sas.page.dashboard']);
    }
}
