<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Setting;

use KejawenLab\ApiSkeleton\Entity\Setting;
use KejawenLab\ApiSkeleton\Form\SettingType;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Setting\Model\SettingInterface;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="SETTING", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class GetAll extends AbstractController
{
    public function __construct(private SettingService $service, private Paginator $paginator)
    {
    }

    /**
     * @Route("/settings", name=GetAll::class, methods={"GET"})
     */
    public function __invoke(Request $request): Response
    {
        $class = new ReflectionClass(Setting::class);
        $setting = new Setting();
        $flashs = $request->getSession()->getFlashBag()->get('form_data');
        foreach ($flashs as $flash) {
            if ($flash instanceof SettingInterface) {
                $setting = $flash;

                $this->addFlash('id', $setting->getId());

                break;
            }
        }

        return $this->render('setting/all.html.twig', [
            'page_title' => 'sas.page.setting.list',
            'context' => StringUtil::lowercase($class->getShortName()),
            'properties' => $class->getProperties(ReflectionProperty::IS_PRIVATE),
            'paginator' => $this->paginator->paginate($this->service->getQueryBuilder(), $request, Setting::class),
            'form' => $this->createForm(SettingType::class, $setting)->createView(),
        ]);
    }
}
