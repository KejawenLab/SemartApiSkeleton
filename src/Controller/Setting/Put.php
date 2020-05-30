<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Controller\Setting;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\Semart\ApiSkeleton\Entity\Setting;
use KejawenLab\Semart\ApiSkeleton\Form\FormFactory;
use KejawenLab\Semart\ApiSkeleton\Form\Type\SettingType;
use KejawenLab\Semart\ApiSkeleton\Setting\Model\SettingInterface;
use KejawenLab\Semart\ApiSkeleton\Setting\SettingService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Psr\Log\LoggerInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Put extends AbstractFOSRestController
{
    private $formFactory;

    private $service;

    private $logger;

    public function __construct(FormFactory $formFactory, SettingService $service, LoggerInterface $auditLogger)
    {
        $this->formFactory = $formFactory;
        $this->service = $service;
        $this->logger = $auditLogger;
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
     * @param Request $request
     * @param string $id
     *
     * @return View
     */
    public function __invoke(Request $request, string $id): View
    {
        $setting = $this->service->get($id);
        if (!$setting instanceof SettingInterface) {
            throw new NotFoundHttpException(sprintf('Setting ID: "%s" not found', $id));
        }

        $form = $this->formFactory->submitRequest(SettingType::class, $request, $setting);
        if (!$form->isValid()) {
            return $this->view((array) $form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        $this->service->save($setting);

        $this->logger->info(sprintf('[%s][%s][%s][%s]', $this->getUser()->getUsername(), __CLASS__, $id, $request->getContent()));

        return $this->view($this->service->get($setting->getId()));
    }
}
