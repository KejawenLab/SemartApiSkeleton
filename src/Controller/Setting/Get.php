<?php

declare(strict_types=1);

namespace App\Controller\Setting;

use App\Entity\Setting;
use App\Setting\SettingService;
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

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    /**
     * @Rest\Get("/settings/{id}")
     * @SWG\Response(
     *     response=200,
     *     description="Return setting detail",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Setting::class, groups={"read"}))
     *     )
     * )
     * @SWG\Tag(name="Setting")
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
