<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller;

use OpenApi\Attributes\Tag;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Post;
use OpenApi\Attributes as OA;
use Symfony\Component\Security\Core\Exception\RuntimeException;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Tag(name: 'Security')]
final class RefreshTokenController extends AbstractFOSRestController
{
    /**
     * @throws RuntimeException
     */
    #[Post(data: '/token/refresh', name: RefreshTokenController::class, priority: 17)]
    #[RequestBody(content: new OA\MediaType(
        mediaType: 'application/json',
        schema: new OA\Schema(
            properties: [new OA\Property(property: 'refresh_token', type: 'string')],
        )
    ))]
    #[Response(
        ref: new OA\Schema(
            properties: [
                new OA\Property(property: 'token', type: 'string'),
                new OA\Property(property: 'refresh_token', type: 'string'),
            ],
        ),
        response: 200,
        description: 'JWT Token',
    )]
    public function __invoke(): never
    {
        throw new RuntimeException('Invalid security configuration');
    }
}
