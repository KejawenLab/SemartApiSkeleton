<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Group;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\Permission;
use KejawenLab\ApiSkeleton\Form\FormFactory;
use KejawenLab\ApiSkeleton\Form\PermissionType;
use KejawenLab\ApiSkeleton\Security\Annotation as Semart;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionInterface;
use KejawenLab\ApiSkeleton\Security\Service\GroupService;
use KejawenLab\ApiSkeleton\Security\Service\PermissionService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Semart\Permission(menu: 'GROUP', actions: [Semart\Permission::EDIT, Semart\Permission::ADD])]
#[Tag(name: 'Group')]
final class PermissionPut extends AbstractFOSRestController
{
    public function __construct(
        private readonly FormFactory $formFactory,
        private readonly GroupService $groupService,
        private readonly PermissionService $permissionService,
        private readonly TranslatorInterface $translator,
    ) {
    }

    #[Put(data: '/groups/{id}/permissions', name: self::class)]
    #[Security(name: 'Bearer')]
    #[RequestBody(
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(ref: new Model(type: PermissionType::class), type: 'object'),
        ),
    )]
    #[OA\Response(
        response: 200,
        description: 'Permission updated',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(ref: new Model(type: Permission::class, groups: ['read']), type: 'object'),
        ),
    )]
    public function __invoke(Request $request, string $id): View
    {
        $group = $this->groupService->get($id);
        if (!$group instanceof GroupInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.group.not_found', [], 'pages'));
        }

        $form = $this->formFactory->submitRequest(PermissionType::class, $request);
        if (!$form->isValid()) {
            return $this->view((array) $form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        /** @var Permission $data */
        $data = $form->getData();
        $permission = $this->permissionService->getPermission($group, $data->getMenu());
        if (!$permission instanceof PermissionInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.permission.not_found', [], 'pages'));
        }

        $permission->setAddable($data->isAddable());
        $permission->setEditable($data->isEditable());
        $permission->setViewable($data->isViewable());
        $permission->setDeletable($data->isDeletable());

        $this->permissionService->save($permission);

        return $this->view($permission);
    }
}
