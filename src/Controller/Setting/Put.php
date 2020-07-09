<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Setting;

use Alpabit\ApiSkeleton\Entity\Setting;
use Alpabit\ApiSkeleton\Form\FormFactory;
use Alpabit\ApiSkeleton\Form\Type\SettingType;
use Alpabit\ApiSkeleton\Security\Annotation\Permission;
use Alpabit\ApiSkeleton\Setting\Model\SettingInterface;
use Alpabit\ApiSkeleton\Setting\SettingService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Noxlogic\RateLimitBundle\Annotation\RateLimit;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Permission(menu="SETTING", actions={Permission::EDIT})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Put extends AbstractFOSRestController
{
    private FormFactory $formFactory;

    private SettingService $service;

    public function __construct(FormFactory $formFactory, SettingService $service)
    {
        $this->formFactory = $formFactory;
        $this->service = $service;
    }

    /**
     * @Rest\Put("/settings/{id}")
     *
     * @SWG\Tag(name="Setting")
     * @SWG\Parameter(
     *     name="setting",
     *     in="body",
     *     type="object",
     *     description="Setting form",
     *     @Model(type=SettingType::class)
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Update setting",
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=Setting::class, groups={"read"})
     *     )
     * )
     *
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
        $setting = $this->service->get($id);
        if (!$setting instanceof SettingInterface) {
            throw new NotFoundHttpException(sprintf('Setting with ID "%s" not found', $id));
        }

        $form = $this->formFactory->submitRequest(SettingType::class, $request, $setting);
        if (!$form->isValid()) {
            return $this->view((array) $form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        $this->service->save($setting);

        return $this->view($this->service->get($setting->getId()));
    }
}
