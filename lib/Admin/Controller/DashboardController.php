<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller;

use KejawenLab\ApiSkeleton\Admin\AdminContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Base;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class DashboardController extends Base
{
    /**
     * @Route(path="/", name=AdminContext::ADMIN_ROUTE, methods={"GET"})
     */
    public function __invoke(): Response
    {
        return $this->render('dashboard/layout.html.twig', ['page_title' => 'sas.page.dashboard']);
    }
}
