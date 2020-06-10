<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\User;

use Alpabit\ApiSkeleton\Entity\User;
use Alpabit\ApiSkeleton\Security\Annotation\Permission;
use Alpabit\ApiSkeleton\Security\Service\UserService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Permission(menu="USER", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Get extends AbstractFOSRestController
{
    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * @Rest\Get("/users/{id}")
     *
     * @SWG\Tag(name="User")
     * @SWG\Response(
     *     response=200,
     *     description="Return user detail",
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=User::class, groups={"read"})
     *     )
     * )
     * @Security(name="Bearer")
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
