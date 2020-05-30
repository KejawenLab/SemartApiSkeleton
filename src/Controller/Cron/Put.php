<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Controller\Cron;

use Cron\CronBundle\Entity\CronJob;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\Semart\ApiSkeleton\Cron\CronService;
use KejawenLab\Semart\ApiSkeleton\Form\FormFactory;
use KejawenLab\Semart\ApiSkeleton\Form\Type\CronType;
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

    public function __construct(FormFactory $formFactory, CronService $service, LoggerInterface $auditLogger)
    {
        $this->formFactory = $formFactory;
        $this->service = $service;
        $this->logger = $auditLogger;
    }

    /**
     * @Rest\Put("/cronjobs/{id}")
     *
     * @SWG\Tag(name="Cron Job")
     * @SWG\Parameter(
     *     name="cronjob",
     *     in="body",
     *     type="object",
     *     description="Cron job form",
     *     @Model(type=CronType::class)
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Update new cron job",
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=CronJob::class, groups={"read"})
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
        $cronjob = $this->service->get($id);
        if (!$cronjob instanceof CronJob) {
            throw new NotFoundHttpException(sprintf('Cron Job ID: "%s" not found', $id));
        }

        $form = $this->formFactory->submitRequest(CronType::class, $request, $cronjob);
        if (!$form->isValid()) {
            return $this->view((array) $form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        $this->service->save($cronjob);

        $this->logger->info(sprintf('[%s][%s][%s]', $this->getUser()->getUsername(), __CLASS__, $request->getContent()));

        return $this->view($this->service->get((string) $cronjob->getId()), Response::HTTP_CREATED);
    }
}
