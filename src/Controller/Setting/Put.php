<?php

declare(strict_types=1);

namespace App\Controller\Setting;

use App\Entity\Setting;
use App\Form\FormFactory;
use App\Form\Type\SettingType;
use App\Setting\Model\SettingInterface;
use App\Setting\SettingService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
class Put extends AbstractFOSRestController
{
    private $formFactory;

    private $service;

    public function __construct(FormFactory $formFactory, SettingService $service)
    {
        $this->formFactory = $formFactory;
        $this->service = $service;
    }

    /**
     * @Rest\Put("/settings/{id}")
     * @SWG\Response(
     *     response=200,
     *     description="Update setting",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Setting::class, groups={"read"}))
     *     )
     * )
     *
     * @SWG\Parameter(
     *     name="setting",
     *     in="body",
     *     type="object",
     *     description="Setting form",
     *     @Model(type=SettingType::class)
     * )
     *
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
        $setting = $this->service->get($id);
        if (!$setting) {
            throw new NotFoundHttpException(sprintf('Setting ID: "%s" not found', $id));
        }

        $form = $this->formFactory->submitRequest(SettingType::class, $request, $setting);
        if (!$form->isValid()) {
            return $this->view($form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        /** @var SettingInterface $setting */
        $setting = $form->getData();
        $this->service->save($setting);

        return $this->view($this->service->get($setting->getId(), true));
    }
}
