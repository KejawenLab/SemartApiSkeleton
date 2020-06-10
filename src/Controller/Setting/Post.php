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
use Psr\Log\LoggerInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Permission(menu="SETTING", actions={Permission::ADD})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Post extends AbstractFOSRestController
{
    private FormFactory $formFactory;

    private SettingService $service;

    private LoggerInterface $logger;

    public function __construct(FormFactory $formFactory, SettingService $service, LoggerInterface $auditLogger)
    {
        $this->formFactory = $formFactory;
        $this->service = $service;
        $this->logger = $auditLogger;
    }

    /**
     * @Rest\Post("/settings")
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
     *     response=201,
     *     description="Crate new setting",
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=Setting::class, groups={"read"})
     *     )
     * )
     *
     * @Security(name="Bearer")
     *
     * @param Request $request
     *
     * @return View
     */
    public function __invoke(Request $request): View
    {
        $form = $this->formFactory->submitRequest(SettingType::class, $request);
        if (!$form->isValid()) {
            return $this->view((array) $form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        /** @var SettingInterface $setting */
        $setting = $form->getData();
        $this->service->save($setting);

        $this->logger->info(sprintf('[%s][%s][%s]', $this->getUser()->getUsername(), __CLASS__, $request->getContent()));

        return $this->view($this->service->get($setting->getId()), Response::HTTP_CREATED);
    }
}
