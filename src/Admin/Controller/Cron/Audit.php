<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Cron;

use DH\DoctrineAuditBundle\Reader\AuditReader;
use KejawenLab\ApiSkeleton\Audit\AuditService;
use KejawenLab\ApiSkeleton\Entity\Cron;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Cron\CronService;
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
    private CronService $service;

    private AuditService $audit;

    private AuditReader $reader;

    public function __construct(CronService $service, AuditService $audit, AuditReader $reader)
    {
        $this->service = $service;
        $this->audit = $audit;
        $this->reader = $reader;
    }

    /**
     * @Route("/cra/{id}/audit", methods={"GET"})
     */
    public function __invoke(Request $request, string $id)
    {
        if (!$entity = $this->service->get($id)) {
            $this->addFlash('error', 'sas.page.cron.not_found');

            return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_cron_getall__invoke'));
        }

        if (!$this->reader->getConfiguration()->isAuditable(Cron::class)) {
            $this->addFlash('error', 'sas.page.audit.not_found');

            return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_cron_getall__invoke'));
        }

        $class = new \ReflectionClass(Cron::class);
        $audit = $this->audit->getAudits($entity, $id)->toArray();

        return $this->render('cron/view.html.twig', [
            'page_title' => 'sas.page.audit.view',
            'context' => StringUtil::lowercase($class->getShortName()),
            'properties' => $class->getProperties(\ReflectionProperty::IS_PRIVATE),
            'data' => $audit['entity'],
            'audits' => $audit['items'],
        ]);
    }
}
