<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Me;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Profile extends AbstractController
{
    /**
     * @Route("/me", methods={"GET"})
     */
    public function __invoke()
    {
        return new Response('Me');
    }
}
