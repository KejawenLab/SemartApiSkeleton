<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Setting;

use KejawenLab\ApiSkeleton\Admin\Controller\AbstractController;
use KejawenLab\ApiSkeleton\Entity\Setting;
use KejawenLab\ApiSkeleton\Form\SettingType;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="SETTING", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Main extends AbstractController
{
    public function __construct(private SettingService $service, Paginator $paginator)
    {
        parent::__construct($this->service, $paginator);
    }

    /**
     * @Route(path="/settings", name=Main::class, methods={"GET", "POST"})
     */
    public function __invoke(Request $request): Response
    {
        $setting = new Setting();
        if ($request->isMethod(Request::METHOD_POST)) {
            $setting = $this->service->get($request->getSession()->get('id'));
        } else {
            $flashs = $request->getSession()->getFlashBag()->get('id');
            foreach ($flashs as $flash) {
                $setting = $this->service->get($flash);
                if (null !== $setting) {
                    $request->getSession()->set('id', $setting->getId());

                    break;
                }
            }
        }

        $form = $this->createForm(SettingType::class, $setting);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->service->save($setting);
                $this->addFlash('info', 'sas.page.setting.saved');

                $form = $this->createForm(SettingType::class);
            }
        }

        return $this->renderList($form, $request, new ReflectionClass(Setting::class));
    }
}
