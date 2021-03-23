<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Setting;

use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use KejawenLab\ApiSkeleton\Audit\AuditService;
use KejawenLab\ApiSkeleton\Entity\Setting;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="AUDIT", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Audit extends AbstractController
{
    private SettingService $service;

    private AuditService $audit;

    private Reader $reader;

    public function __construct(SettingService $service, AuditService $audit, Reader $reader)
    {
        $this->service = $service;
        $this->audit = $audit;
        $this->reader = $reader;
    }

    /**
     * @Route("/settings/{id}/audit", methods={"GET"}, priority=-255)
     */
    public function __invoke(string $id): Response
    {
        if (!$entity = $this->service->get($id)) {
            $this->addFlash('error', 'sas.page.setting.not_found');

            return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_setting_getall__invoke'));
        }

        if (!$this->reader->getProvider()->isAuditable(Setting::class)) {
            $this->addFlash('error', 'sas.page.audit.not_found');

            return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_setting_getall__invoke'));
        }

        $class = new \ReflectionClass(Setting::class);
        $audit = $this->audit->getAudits($entity, $id)->toArray();

        return $this->render('setting/view.html.twig', [
            'page_title' => 'sas.page.audit.view',
            'context' => StringUtil::lowercase($class->getShortName()),
            'properties' => $class->getProperties(\ReflectionProperty::IS_PRIVATE),
            'data' => $audit['entity'],
            'audits' => $audit['items'],
        ]);
    }
}
