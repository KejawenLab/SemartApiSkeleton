<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Setting;

use KejawenLab\ApiSkeleton\Form\SettingType;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Setting\Model\SettingInterface;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="SETTING", actions={Permission::EDIT})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Put extends AbstractController
{
    private SettingService $service;

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/settings/{id}/edit", methods={"GET", "POST"}, priority=1)
     */
    public function __invoke(Request $request, string $id): Response
    {
        $setting = $this->service->get($id);
        if (!$setting instanceof SettingInterface) {
            $this->addFlash('error', 'sas.page.setting.not_found');

            return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_setting_getall__invoke'));
        }

        $form = $this->createForm(SettingType::class, $setting);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->service->save($setting);

                $this->addFlash('info', 'sas.page.setting.saved');

                return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_setting_getall__invoke'));
            }
        }

        return $this->render('setting/form.html.twig', [
            'page_title' => 'sas.page.setting.edit',
            'form' => $form->createView(),
        ]);
    }
}
