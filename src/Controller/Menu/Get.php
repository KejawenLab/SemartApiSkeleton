<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Menu;

use Alpabit\ApiSkeleton\Entity\Menu;
use Alpabit\ApiSkeleton\Security\Annotation\Permission;
use Alpabit\ApiSkeleton\Security\Service\MenuService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Noxlogic\RateLimitBundle\Annotation\RateLimit;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Permission(menu="MENU", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Get extends AbstractFOSRestController
{
    private MenuService $service;

    public function __construct(MenuService $service)
    {
        $this->service = $service;
    }

    /**
     * @Rest\Get("/menus/{id}")
     *
     * @SWG\Tag(name="Menu")
     * @SWG\Response(
     *     response=200,
     *     description="Return menu detail",
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=Menu::class, groups={"read"})
     *     )
     * )
     * @Security(name="Bearer")
     *
     * @RateLimit(limit=7, period=1)
     *
     * @param Request $request
     * @param string $id
     *
     * @return View
     */
    public function __invoke(Request $request, string $id): View
    {
        return $this->view($this->service->get($id));
    }
}
