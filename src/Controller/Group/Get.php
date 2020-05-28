<?php

declare(strict_types=1);

namespace App\Controller\Group;


use App\Entity\Group;
use App\Security\Service\GroupService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
class Get extends AbstractFOSRestController
{
    private $service;

    public function __construct(GroupService $service)
    {
        $this->service = $service;
    }

    /**
     * @Rest\Get("/groups/{id}")
     *
     * @SWG\Tag(name="Group")
     * @SWG\Response(
     *     response=200,
     *     description="Return group detail",
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=Group::class, groups={"read"})
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
        return $this->view($this->service->get($id, true));
    }
}
