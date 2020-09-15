<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Cron;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Cron\CronService;
use KejawenLab\ApiSkeleton\Entity\Cron;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Nelmio\ApiDocBundle\Annotation\Security;
use Noxlogic\RateLimitBundle\Annotation\RateLimit;
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
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Run extends AbstractFOSRestController
{
    private CronService $service;

    private KernelInterface $kernel;

    public function __construct(CronService $service, KernelInterface $kernel)
    {
        $this->service = $service;
        $this->kernel = $kernel;
    }

    /**
     * @Rest\Post("/cronjobs/{id}/run", priority=-17)
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
     * @RateLimit(limit=7, period=1)
     *
     * @throws \Exception
     */
    public function __invoke(Request $request, string $id): View
    {
        $cron = $this->service->get($id);
        if (!$cron instanceof Cron) {
            throw new NotFoundHttpException(sprintf('Cron Job with ID "%s" not found', $id));
        }

        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'semart:cron:run',
            'job' => $cron->getId(),
            '--schedule_now' => null,
        ]);

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
