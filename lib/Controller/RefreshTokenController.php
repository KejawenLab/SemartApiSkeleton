<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller;

use OpenApi\Attributes\Tag;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Post;
use OpenApi\Annotations as OA;
use Symfony\Component\Security\Core\Exception\RuntimeException;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class RefreshTokenController extends AbstractFOSRestController
{
    /**
     *
     * @throws RuntimeException
     */
    #[Post(data: '/token/refresh', name: RefreshTokenController::class, priority: 17)]
    #[Tag(name: 'Security')]
    #[RequestBody(content: [new OA\MediaType(mediaType: 'application/json', new OA\Schema(new OA\Property(property: 'refresh_token', type: 'string')))])]
    #[Response(response: 200, description: 'JWT Token', new OA\Schema(new OA\Property(property: 'token', type: 'string'), new OA\Property(property: 'refresh_token', type: 'string')))]
    public function __invoke(): never
    {
        throw new RuntimeException('Invalid security configuration');
    }
}
