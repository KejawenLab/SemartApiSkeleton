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
use Noxlogic\RateLimitBundle\Annotation\RateLimit;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Semart\Permission(menu="GROUP", actions={Semart\Permission::EDIT, Semart\Permission::ADD})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class PermissionPut extends AbstractFOSRestController
{
    private FormFactory $formFactory;

    private GroupService $groupService;

    private PermissionService $permissionService;

    public function __construct(FormFactory $formFactory, GroupService $groupService, PermissionService $permissionService)
    {
        $this->formFactory = $formFactory;
        $this->groupService = $groupService;
        $this->permissionService = $permissionService;
    }

    /**
     * @Rest\Put("/groups/{id}/permissions")
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
     *
     * @RateLimit(limit=7, period=1)
     */
    public function __invoke(Request $request, string $id): View
    {
        $group = $this->groupService->get($id);
        if (!$group instanceof GroupInterface) {
            throw new NotFoundHttpException(sprintf('Group with ID "%s" not found', $id));
        }

        $form = $this->formFactory->submitRequest(PermissionType::class, $request);
        if (!$form->isValid()) {
            return $this->view((array) $form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        /** @var Permission $data */
        $data = $form->getData();
        $permission = $this->permissionService->getPermission($group, $data->getMenu());
        if (!$permission instanceof PermissionInterface) {
            throw new NotFoundHttpException(sprintf('Permission for Menu with ID "%s" not found', $data->getMenu()->getId()));
        }

        $permission->setAddable($data->isAddable());
        $permission->setEditable($data->isEditable());
        $permission->setViewable($data->isViewable());
        $permission->setDeletable($data->isDeletable());

        $this->permissionService->save($permission);

        return $this->view($permission);
    }
}
