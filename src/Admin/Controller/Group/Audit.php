<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Group;

use DH\DoctrineAuditBundle\Reader\AuditReader;
use KejawenLab\ApiSkeleton\Audit\AuditService;
use KejawenLab\ApiSkeleton\Entity\Group;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Service\GroupService;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="AUDIT", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Audit extends AbstractController
{
    private GroupService $service;

    private AuditService $audit;

    private AuditReader $reader;

    public function __construct(GroupService $service, AuditService $audit, AuditReader $reader)
    {
        $this->service = $service;
        $this->audit = $audit;
        $this->reader = $reader;
    }

    /**
     * @Route("/groups/{id}/audit", methods={"GET"})
     */
    public function __invoke(Request $request, string $id)
    {
        if (!$group = $this->service->get($id)) {
            $this->addFlash('error', 'sas.page.group.not_found');

            return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_group_getall__invoke'));
        }

        if (!$this->reader->getConfiguration()->isAuditable(Group::class)) {
            $this->addFlash('error', 'sas.page.audit.not_found');

            return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_group_getall__invoke'));
        }

        $class = new \ReflectionClass(Group::class);
        $audit = $this->audit->getAudits($group, $id)->toArray();

        return $this->render('group/view.html.twig', [
            'page_title' => 'sas.page.audit.view',
            'context' => StringUtil::lowercase($class->getShortName()),
            'properties' => $class->getProperties(\ReflectionProperty::IS_PRIVATE),
            'data' => $audit['entity'],
            'audits' => $audit['items'],
        ]);
    }
}
