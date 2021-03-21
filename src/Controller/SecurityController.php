<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Noxlogic\RateLimitBundle\Annotation\RateLimit;
use OpenApi\Annotations as SWG;
use Symfony\Component\Security\Core\Exception\RuntimeException;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class SecurityController extends AbstractFOSRestController
{
    public const ROUTE_NAME = 'api_login';

    /**
     * @Rest\Post("/login", name=SecurityController::ROUTE_NAME, priority=17)
     *
     * @SWG\Tag(name="Security")
     * @SWG\RequestBody(
     *     @SWG\Schema(
     *         @SWG\Property(property="username", type="string"),
     *         @SWG\Property(property="password", type="string")
     *     )
     * )
     * @SWG\Response(
     *     response=200,
     *     description="JWT Token",
     *     @SWG\Schema(
     *         @SWG\Property(property="token", type="string")
     *     )
     * )
     *
     * @RateLimit(limit=7, period=1)
     */
    public function __invoke()
    {
        throw new RuntimeException('Invalid security configuration');
    }
}
