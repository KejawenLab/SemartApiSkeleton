<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Me;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\Menu as Entity;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Service\PermissionService;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

/**
 * @Permission(menu="PROFILE", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Menu extends AbstractFOSRestController
{
    private PermissionService $service;

    public function __construct(PermissionService $service)
    {
        $this->service = $service;
    }

    /**
     * @Rest\Get("/me/menus", priority=1)
     *
     * @OA\Tag(name="Profile")
     * @OA\Response(
     *     response=200,
     *     description= "Menu list for logged user",
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(ref=@Model(type=Entity::class, groups={"read"}))
     *             )
     *         )
     *     }
     * )
     * @Security(name="Bearer")
     */
    public function __invoke(UserProviderFactory $userProviderFactory): View
    {
        return $this->view($this->service->getByUser($userProviderFactory->getRealUser($this->getUser())));
    }
}
