<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Me;

use Alpabit\ApiSkeleton\Entity\Menu as Entity;
use Alpabit\ApiSkeleton\Security\Annotation\Permission;
use Alpabit\ApiSkeleton\Security\Service\PermissionService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Permission(menu="PROFILE", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
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
     * @SWG\Tag(name="Profile")
     * @SWG\Response(
     *     response=200,
     *     description="Return menu list for logged user",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Entity::class, groups={"read"}))
     *     )
     * )
     * @Security(name="Bearer")
     *
     * @param Request $request
     *
     * @return View
     */
    public function __invoke(Request $request): View
    {
        return $this->view($this->service->getByUser($this->getUser()));
    }
}
