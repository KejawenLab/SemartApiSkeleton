<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller;

use KejawenLab\ApiSkeleton\SemartApiSkeleton;
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
            'semart_name' => 'Semart Api Skeleton',
            'semart_codename' => SemartApiSkeleton::CODENAME,
            'semart_version' => SemartApiSkeleton::VERSION,
            'app_name' => $_SERVER['APP_TITLE'],
            'app_description' => $_SERVER['APP_DESCRIPTION'],
            'app_version' => $_SERVER['APP_VERSION'],
        ]);
    }
}
