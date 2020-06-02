<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Cron;

use Alpabit\ApiSkeleton\Cron\CronService;
use Alpabit\ApiSkeleton\Entity\Cron;
use Alpabit\ApiSkeleton\Security\Annotation\Permission;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
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
 * @Permission(menu="CRON", actions={Permission::ADD, Permission::EDIT})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Run extends AbstractFOSRestController
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
     * @SWG\Tag(name="Cron")
     * @SWG\Response(
     *     response=200,
     *     description="Job status",
     *     @SWG\Schema(
     *         @SWG\Property(property="code", type="integer"),
     *         @SWG\Property(property="message", type="string")
     *     )
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
        $cron = $this->service->get($id);
        if (!$cron instanceof Cron) {
            throw new NotFoundHttpException(sprintf('Cron Job with ID "%s" not found', $id));
        }

        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'semart:cron:run',
            'job' => $cron->getName(),
            '--schedule_now' => null,
        ]);

        $cron->setRunning(true);
        $this->service->save($cron);

        $return = $application->run($input, new NullOutput());
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
