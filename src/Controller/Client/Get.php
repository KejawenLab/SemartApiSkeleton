<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Client;

use Alpabit\ApiSkeleton\Entity\Client;
use Alpabit\ApiSkeleton\Client\ClientService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Alpabit\ApiSkeleton\Security\Annotation\Permission;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Noxlogic\RateLimitBundle\Annotation\RateLimit;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Permission(menu="CLIENT", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Get extends AbstractFOSRestController
{
    private ClientService $service;

    public function __construct(ClientService $service)
    {
        $this->service = $service;
    }

    /**
     * @Rest\Get("/clients/{id}")
     *
     * @SWG\Tag(name="Client")
     * @SWG\Response(
     *     response=200,
     *     description="Return client detail",
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=Client::class, groups={"read"})
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
