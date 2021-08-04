<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller;

use KejawenLab\ApiSkeleton\SemartApiSkeleton;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class HealthController extends AbstractController
{
    public function __invoke(): Response
    {
        return new JsonResponse([
            'semart' => [
                'name' => 'Semart Api Skeleton',
                'version' => [
                    'codename' => SemartApiSkeleton::CODENAME,
                    'alias' => SemartApiSkeleton::VERSION,
                    'number' => SemartApiSkeleton::getVersionNumber(),
                ],
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
