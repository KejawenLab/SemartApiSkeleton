<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Cron;

use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use KejawenLab\ApiSkeleton\Cron\CronService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="CRON", actions={Permission::DELETE})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Delete extends AbstractController
{
    private CronService $service;

    public function __construct(CronService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/cra/{id}/delete", methods={"GET"})
     */
    public function __invoke(Request $request, string $id)
    {
        $cron = $this->service->get($id);
        if (!$cron instanceof CronInterface) {
            $this->addFlash('error', 'sas.page.cron.not_found');

            return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_cron_getall__invoke'));
        }

        $this->service->remove($cron);

        $this->addFlash('info', 'sas.page.cron.deleted');

        return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_cron_getall__invoke'));
    }
}
