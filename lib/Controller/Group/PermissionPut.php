<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Group;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
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
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Semart\Permission(menu="GROUP", actions={Semart\Permission::EDIT, Semart\Permission::ADD})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class PermissionPut extends AbstractFOSRestController
{
    public function __construct(
        private FormFactory $formFactory,
        private GroupService $groupService,
        private PermissionService $permissionService,
        private TranslatorInterface $translator,
    ) {
    }

    /**
     * @Rest\Put("/groups/{id}/permissions", name=PermissionPut::class)
     *
     * @OA\Tag(name="Group")
     * @OA\RequestBody(
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 ref=@Model(type=PermissionType::class)
     *             )
     *         )
     *     }
     * )
     * @OA\Response(
     *     response=200,
     *     description= "Permission updated",
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 ref=@Model(type=Permission::class, groups={"read"})
     *             )
     *         )
     *     }
     * )
     *
     * @Security(name="Bearer")
     */
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
