<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Setting;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\Setting;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
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
     * @OA\Tag(name="Setting")
     * @OA\Response(
     *     response=200,
     *     description= "Setting list",
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(ref=@Model(type=Setting::class, groups={"read"}))
     *             )
     *         )
     *     }
     * )
     */
    public function __invoke(Request $request, string $id): View
    {
        return $this->view($this->service->getPublicSetting($id));
    }
}
