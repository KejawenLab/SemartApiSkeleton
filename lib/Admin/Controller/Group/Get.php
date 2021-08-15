<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Group;

use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use KejawenLab\ApiSkeleton\Audit\AuditService;
use KejawenLab\ApiSkeleton\Entity\Group;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Security\Service\GroupService;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="GROUP", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Get extends AbstractController
{
    public function __construct(private GroupService $service, private AuditService $audit, private Reader $reader)
    {
    }

    /**
     * @Route("/groups/{id}", name=Get::class, methods={"GET"})
     */
    public function __invoke(string $id): Response
    {
        $group = $this->service->get($id);
        if (!$group instanceof GroupInterface) {
            $this->addFlash('error', 'sas.page.group.not_found');

            return new RedirectResponse($this->generateUrl(GetAll::class));
        }

        $audit = ['items' => []];
        if ($this->reader->getProvider()->isAuditable(Group::class)) {
            $audit = $this->audit->getAudits($group, $id, 3)->toArray();
        }

        $class = new ReflectionClass(Group::class);

        return $this->render('group/view.html.twig', [
            'page_title' => 'sas.page.group.view',
            'context' => StringUtil::lowercase($class->getShortName()),
            'properties' => $class->getProperties(ReflectionProperty::IS_PRIVATE),
            'data' => $group,
            'audits' => $audit['items'],
        ]);
    }
}
