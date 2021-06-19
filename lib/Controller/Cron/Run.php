<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Cron;

use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Cron\CronService;
use KejawenLab\ApiSkeleton\Entity\Cron;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @Permission(menu="CRON", actions={Permission::ADD, Permission::EDIT})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Run extends AbstractFOSRestController
{
    public function __construct(private CronService $service, private KernelInterface $kernel)
    {
    }

    /**
     * @Rest\Post("/cronjobs/{id}/run", name=Run::class, priority=-17)
     *
     * @OA\Tag(name="Cron")
     * @OA\Response(
     *     response=200,
     *     description= "Job status",
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 properties={
     *                     @OA\Property(property="code", type="integer"),
     *                     @OA\Property(property="message", type="string")
     *                 }
     *             )
     *         )
     *     }
     * )
     *
     * @Security(name="Bearer")
     *
     * @throws Exception
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
