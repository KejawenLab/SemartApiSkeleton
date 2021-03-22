<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Me;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\User;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Noxlogic\RateLimitBundle\Annotation\RateLimit;
use OpenApi\Annotations as OA;

/**
 * @Permission(menu="PROFILE", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Profile extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/me", priority=1)
     *
     * @OA\Tag(name="Profile")
     * @OA\Response(
     *     response=200,
     *     description= "User profile",
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 ref=@Model(type=User::class, groups={"read"})
     *             )
     *         )
     *     }
     * )
     *
     * @Security(name="Bearer")
     *
     * @RateLimit(limit=7, period=1)
     */
    public function __invoke(UserProviderFactory $userProviderFactory): View
    {
        return $this->view($userProviderFactory->getRealUser($this->getUser()));
    }
}
