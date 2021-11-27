<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Setting;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Post as Route;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\Setting;
use KejawenLab\ApiSkeleton\Form\FormFactory;
use KejawenLab\ApiSkeleton\Form\SettingType;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Setting\Model\SettingInterface;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Permission(menu="SETTING", actions={Permission::ADD})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Post extends AbstractFOSRestController
{
    public function __construct(private FormFactory $formFactory, private SettingService $service)
    {
    }

    /**
     * @OA\Tag(name="Setting")
     * @OA\RequestBody(
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 ref=@Model(type=SettingType::class)
     *             )
     *         )
     *     }
     * )
     * @OA\Response(
     *     response=201,
     *     description= "Setting created",
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 ref=@Model(type=Setting::class, groups={"read"})
     *             )
     *         )
     *     }
     * )
     *
     * @Security(name="Bearer")
     */
    #[Route(data: '/settings', name: Post::class)]
    public function __invoke(Request $request): View
    {
        $form = $this->formFactory->submitRequest(SettingType::class, $request);
        if (!$form->isValid()) {
            return $this->view((array) $form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        /** @var SettingInterface $setting */
        $setting = $form->getData();
        $this->service->save($setting);

        return $this->view($this->service->get($setting->getId()), Response::HTTP_CREATED);
    }
}
