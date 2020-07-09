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
use Noxlogic\RateLimitBundle\Annotation\RateLimit;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class PublicAllSetting extends AbstractFOSRestController
{
    private SettingService $service;

    private Paginator $paginator;

    public function __construct(SettingService $service, Paginator $paginator)
    {
        $this->service = $service;
        $this->paginator = $paginator;
    }

    /**
     * @Rest\Get("/settings/public", priority=1)
     *
     * @SWG\Tag(name="Setting")
     * @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     type="string",
     *     description="Page indicator"
     * )
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="string",
     *     description="Records per page"
     * )
     * @SWG\Parameter(
     *     name="q",
     *     in="query",
     *     type="string",
     *     description="Search setting by parameter"
     * )
     * @SWG\Parameter(
     *     name="parameter",
     *     in="query",
     *     type="string",
     *     description="Filter setting by parameter"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Return setting list",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Setting::class, groups={"read"}))
     *     )
     * )
     *
     * @RateLimit(limit=17, period=1)
     *
     * @param Request $request
     *
     * @return View
     */
    public function __invoke(Request $request): View
    {
        return $this->view($this->paginator->paginate($this->service->getQueryBuilder(), $request, Setting::class));
    }
}
