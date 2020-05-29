<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Controller\Cron;

use Cron\CronBundle\Entity\CronJob;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\Semart\ApiSkeleton\Cron\CronService;
use Nelmio\ApiDocBundle\Annotation\Security;
use Psr\Log\LoggerInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
class Delete extends AbstractFOSRestController
{
    private $service;

    private $logger;

    public function __construct(CronService $service, LoggerInterface $auditLogger)
    {
        $this->service = $service;
        $this->logger = $auditLogger;
    }

    /**
     * @Rest\Delete("/cronjobs/{id}")
     *
     * @SWG\Tag(name="Cron Job")
     * @SWG\Response(
     *     response=204,
     *     description="Delete cron job"
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

        $this->logger->info(sprintf('[%s][%s][%s]', $this->getUser()->getUsername(), __CLASS__, $id));

        $this->service->remove($cronjob);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
