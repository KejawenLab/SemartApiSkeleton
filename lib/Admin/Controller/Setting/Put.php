<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Setting;

use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Setting\Model\SettingInterface;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'SETTING', actions: [Permission::EDIT])]
final class Put extends AbstractController
{
    public function __construct(private readonly SettingService $service)
    {
    }

    #[Route(path: '/settings/{id}/edit', name: Put::class, methods: ['GET'], priority: 1)]
    public function __invoke(Request $request, string $id): Response
    {
        $setting = $this->service->get($id);
        if (!$setting instanceof SettingInterface) {
            $this->addFlash('error', 'sas.page.setting.not_found');

            return new RedirectResponse($this->generateUrl(Main::class, $request->query->all()));
        }

        $this->addFlash('id', $setting->getId());

        return new RedirectResponse($this->generateUrl(Main::class, $request->query->all()));
    }
}
