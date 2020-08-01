<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Setting;

use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Setting\Model\SettingInterface;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="SETTING", actions={Permission::DELETE})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Delete extends AbstractController
{
    private SettingService $service;

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/settings/{id}/delete", methods={"GET"})
     */
    public function __invoke(Request $request, string $id)
    {
        $setting = $this->service->get($id);
        if (!$setting instanceof SettingInterface) {
            $this->addFlash('error', 'sas.page.setting.not_found');

            return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_setting_getall__invoke'));
        }

        $this->service->remove($setting);

        $this->addFlash('info', 'sas.page.setting.deleted');

        return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_setting_getall__invoke'));
    }
}
