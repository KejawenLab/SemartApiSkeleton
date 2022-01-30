<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Post;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Tag;
use Symfony\Component\Security\Core\Exception\RuntimeException;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Tag(name: 'Security')]
final class SecurityController extends AbstractFOSRestController
{
    #[Post(data: '/login', name: SecurityController::class, priority: 17)]
    #[RequestBody(
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'username', type: 'string'),
                    new OA\Property(property: 'password', type: 'string'),
                ],
            ),
        ),
    )]
    #[Response(
        response: 200,
        description: 'JWT Token',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'token', type: 'string'),
                    new OA\Property(property: 'refresh_token', type: 'string'),
                ],
            ),
        ),
    )]
    public function __invoke(): never
    {
        throw new RuntimeException('Invalid security configuration');
    }
}
