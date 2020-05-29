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
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
class Run extends AbstractFOSRestController
{
    private $service;

    private $logger;

    private $kernel;

    public function __construct(CronService $service, LoggerInterface $auditLogger, KernelInterface $kernel)
    {
        $this->service = $service;
        $this->logger = $auditLogger;
        $this->kernel = $kernel;
    }

    /**
     * @Rest\Post("/cronjobs/{id}/run")
     *
     * @SWG\Tag(name="Cron Job")
     * @SWG\Response(
     *     response=200,
     *     description="Run job manually"
     * )
     * @Security(name="Bearer")
     *
     * @param Request $request
     * @param string $id
     *
     * @return View
     *
     * @throws \Exception
     */
    public function __invoke(Request $request, string $id): View
    {
        $this->logger->info(sprintf('[%s][%s][%s][%s]', $this->getUser()->getUsername(), __CLASS__, $id, serialize($request->query->all())));
        /** @var CronJob $job */
        $job = $this->service->get($id);
        if (!$job) {
            throw new NotFoundHttpException();
        }

        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'cron:run',
            'job' => $job->getName(),
            '--schedule_now' => null,
        ]);

        $output = new NullOutput();
        $return = $application->run($input, $output);
        $message = 'Job running successfully';
        if (0 !== $return) {
            $message = 'Job can\'t be run. Please check job report';
        }

        return $this->view([
            'code' => $return,
            'message' => $message,
        ]);
    }
}
