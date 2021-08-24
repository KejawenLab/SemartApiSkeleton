<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Cron;

use KejawenLab\ApiSkeleton\Cron\CronService;
use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="CRON", actions={Permission::EDIT})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Run extends AbstractController
{
    public function __construct(private CronService $service, private KernelInterface $kernel)
    {
    }

    /**
     * @Route(path="/crons/{id}/run", name=Run::class, methods={"GET"}, priority=-17)
     */
    public function __invoke(string $id): Response
    {
        $cron = $this->service->get($id);
        if (!$cron instanceof CronInterface) {
            $this->addFlash('error', 'sas.page.cron.not_found');

            return new RedirectResponse($this->generateUrl(Main::class));
        }

        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'semart:cron:run',
            'job' => $cron->getId(),
            '--schedule_now' => null,
        ]);

        $return = $application->run($input, new NullOutput());
        if (0 === $return) {
            $this->addFlash('info', 'sas.page.cron.run_success');
        } else {
            $this->addFlash('error', 'sas.page.cron.run_failed');
        }

        return new RedirectResponse($this->generateUrl(Get::class, ['id' => $id]));
    }
}
