<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Cron;

use Alpabit\ApiSkeleton\Cron\CronService;
use Alpabit\ApiSkeleton\Entity\Cron;
use Alpabit\ApiSkeleton\Security\Annotation\Permission;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Permission(menu="CRON", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
*/
final class Get extends AbstractFOSRestController
{
    private CronService $service;

    public function __construct(CronService $service)
    {
        $this->service = $service;
    }

    /**
    * @Rest\Get("/cronjobs/{id}")
    *
    * @SWG\Tag(name="Cron")
    * @SWG\Response(
    *     response=200,
    *     description="Return cron detail",
    *     @SWG\Schema(
    *         type="object",
    *         ref=@Model(type=Cron::class, groups={"read"})
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
