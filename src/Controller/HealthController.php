<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller;

use KejawenLab\ApiSkeleton\SemartApiSkeleton;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class HealthController extends AbstractController
{
    public function __invoke()
    {
        return new JsonResponse([
            'semart' => [
                'name' => 'Semart Api Skeleton',
                'codename' => SemartApiSkeleton::CODENAME,
                'version' => SemartApiSkeleton::VERSION,
                'author' => 'https://github.com/KejawenLab',
                'maintainer' => 'https://github.com/ad3n',
            ],
            'app' => [
                'name' => $_SERVER['APP_TITLE'],
                'description' => $_SERVER['APP_DESCRIPTION'],
                'version' => $_SERVER['APP_VERSION'],
            ],
        ]);
    }
}