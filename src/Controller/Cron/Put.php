<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Cron;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Cron\CronService;
use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use KejawenLab\ApiSkeleton\Entity\Cron;
use KejawenLab\ApiSkeleton\Form\CronType;
use KejawenLab\ApiSkeleton\Form\FormFactory;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Noxlogic\RateLimitBundle\Annotation\RateLimit;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Permission(menu="CRON", actions={Permission::EDIT})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Put extends AbstractFOSRestController
{
    private FormFactory $formFactory;

    private CronService $service;

    public function __construct(FormFactory $formFactory, CronService $service)
    {
        $this->formFactory = $formFactory;
        $this->service = $service;
    }

    /**
     * @Rest\Put("/cronjobs/{id}")
     *
     * @SWG\Tag(name="Cron")
     * @SWG\Parameter(
     *     name="cron",
     *     in="body",
     *     type="object",
     *     description="Cron form",
     *     @Model(type=CronType::class)
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Update cron",
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=Cron::class, groups={"read"})
     *     )
     * )
     *
     * @Security(name="Bearer")
     *
     * @RateLimit(limit=7, period=1)
     */
    public function __invoke(Request $request, string $id): View
    {
        $cron = $this->service->get($id);
        if (!$cron instanceof CronInterface) {
            throw new NotFoundHttpException(sprintf('Cron ID: "%s" not found', $id));
        }

        $form = $this->formFactory->submitRequest(CronType::class, $request, $cron);
        if (!$form->isValid()) {
            return $this->view((array) $form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        $this->service->save($cron);

        return $this->view($this->service->get($cron->getId()));
    }
}
