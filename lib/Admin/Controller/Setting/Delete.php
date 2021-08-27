<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Setting;

use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Setting\Model\SettingInterface;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="SETTING", actions={Permission::DELETE})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Delete extends AbstractController
{
    public function __construct(private SettingService $service)
    {
    }

    #[Route(path: '/settings/{id}/delete', name: Delete::class, methods: ['GET'])]
    public function __invoke(string $id) : Response
    {
        $setting = $this->service->get($id);
        if (!$setting instanceof SettingInterface) {
            $this->addFlash('error', 'sas.page.setting.not_found');

            return new RedirectResponse($this->generateUrl(Main::class));
        }
        if (!$setting->isReserved()) {
            $this->addFlash('error', 'sas.page.setting.reserved_not_allowed');

            return new RedirectResponse($this->generateUrl(Main::class));
        }
        $this->service->remove($setting);
        $this->addFlash('info', 'sas.page.setting.deleted');
        return new RedirectResponse($this->generateUrl(Main::class));
    }
}
