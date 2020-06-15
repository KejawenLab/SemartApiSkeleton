<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Me;

use Alpabit\ApiSkeleton\Entity\User;
use Alpabit\ApiSkeleton\Security\Annotation\Permission;
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
final class Profile extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/me", priority=1)
     *
     * @SWG\Tag(name="Profile")
     * @SWG\Response(
     *     response=200,
     *     description="Return profile detail",
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=User::class, groups={"read"})
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
        return $this->view($this->getUser());
    }
}
