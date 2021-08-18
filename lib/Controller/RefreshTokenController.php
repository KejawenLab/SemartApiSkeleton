<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\RuntimeException;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class RefreshTokenController extends AbstractFOSRestController
{
    /**
     * @Rest\Post("/token/refresh", name=RefreshTokenController::class, priority=17)
     *
     * @OA\Tag(name="Security")
     * @OA\RequestBody(
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="refresh_token", type="string")
     *             )
     *         )
     *     }
     * )
     * @OA\Response(
     *     response=200,
     *     description="JWT Token",
     *     @OA\Schema(
     *         @OA\Property(property="token", type="string"),
     *         @OA\Property(property="refresh_token", type="string")
     *     )
     * )
     *
     * @throws RuntimeException
     */
    public function __invoke(): Response
    {
        throw new RuntimeException('Invalid security configuration');
    }
}
