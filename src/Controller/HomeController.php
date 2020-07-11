<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class HomeController extends AbstractController
{
    public function __invoke()
    {
        return new JsonResponse([
            'name' => $_SERVER['APP_TITLE'],
            'description' => $_SERVER['APP_DESCRIPTION'],
            'version' => $_SERVER['APP_VERSION'],
        ]);
    }
}
