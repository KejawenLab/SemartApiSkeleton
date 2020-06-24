<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Setting;

use Alpabit\ApiSkeleton\Entity\Setting;
use Alpabit\ApiSkeleton\Pagination\Paginator;
use Alpabit\ApiSkeleton\Setting\SettingService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class PublicGetSetting extends AbstractFOSRestController
{
    private SettingService $service;

    private Paginator $paginator;

    public function __construct(SettingService $service, Paginator $paginator)
    {
        $this->service = $service;
        $this->paginator = $paginator;
    }

    /**
     * @Rest\Get("/settings/public/{id}", priority=1)
     *
     * @SWG\Tag(name="Setting")
     * @SWG\Response(
     *     response=200,
     *     description="Return setting detail",
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=Setting::class, groups={"read"})
     *     )
     * )
     *
     * @param Request $request
     * @param string $id
     *
     * @return View
     */
    public function __invoke(Request $request, string $id): View
    {
        return $this->view($this->service->getPublicSetting($id));
    }
}
