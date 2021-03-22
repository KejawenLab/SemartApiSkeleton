<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class HomeController extends AbstractController
{
    public function __invoke()
    {
        return new RedirectResponse($this->generateUrl('admin_home'));
    }
}
