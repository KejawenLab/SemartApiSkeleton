<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Group;

use KejawenLab\ApiSkeleton\Security\Annotation as Semart;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Service\GroupService;
use KejawenLab\ApiSkeleton\Security\Service\PermissionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Semart\Permission(menu="GROUP", actions={Semart\Permission::EDIT, Semart\Permission::ADD})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class PermissionPut extends AbstractController
{
    public function __construct(private GroupService $groupService, private PermissionService $service)
    {
    }

    /**
     * @Route("/groups/{groupId}/permissions/{id}", name=PermissionPut::class, methods={"POST"})
     */
    public function __invoke(Request $request, string $groupId, string $id): Response
    {
        $group = $this->groupService->get($groupId);
        if (!$group) {
            $this->addFlash('error', 'sas.page.permission.group_not_found');

            return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_group_permission__invoke', ['id' => $groupId]));
        }

        /** @var \KejawenLab\ApiSkeleton\Entity\Permission $permission */
        $permission = $this->service->get($id);
        if (!$permission) {
            $this->addFlash('error', 'sas.page.permission.not_found');

            return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_group_permission__invoke', ['id' => $groupId]));
        }

        $type = $request->request->get('type');
        $value = false;
        if ('true' === $request->request->get('value')) {
            $value = true;
        }

        switch ($type) {
            case Permission::ADD:
                $permission->setAddable($value);
                break;
            case Permission::EDIT:
                $permission->setEditable($value);
                break;
            case Permission::VIEW:
                $permission->setViewable($value);
                break;
            case Permission::DELETE:
                $permission->setDeletable($value);
                break;
        }

        $this->service->save($permission);

        $this->addFlash('info', 'sas.page.permission.saved');

        return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_group_permission__invoke', ['id' => $groupId]));
    }
}
