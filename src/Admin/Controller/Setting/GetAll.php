<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Setting;

use KejawenLab\ApiSkeleton\Entity\Setting;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="SETTING", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class GetAll extends AbstractController
{
    private SettingService $service;

    private Paginator $paginator;

    public function __construct(SettingService $service, Paginator $paginator)
    {
        $this->service = $service;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/settings", methods={"GET"})
     */
    public function __invoke(Request $request)
    {
        $class = new \ReflectionClass(Setting::class);

        return $this->render('setting/all.html.twig', [
            'page_title' => 'sas.page.setting.list',
            'context' => StringUtil::lowercase($class->getShortName()),
            'properties' => $class->getProperties(\ReflectionProperty::IS_PRIVATE),
            'paginator' => $this->paginator->paginate($this->service->getQueryBuilder(), $request, Setting::class),
        ]);
    }
}
