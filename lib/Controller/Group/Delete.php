<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Group;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Security\Service\GroupService;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Permission(menu="GROUP", actions={Permission::DELETE})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Delete extends AbstractFOSRestController
{
    private GroupService $service;

    public function __construct(GroupService $service)
    {
        $this->service = $service;
    }

    /**
     * @Rest\Delete("/groups/{id}")
     *
     * @OA\Tag(name="Group")
     * @OA\Response(
     *     response=204,
     *     description="Delete group"
     * )
     *
     * @Security(name="Bearer")
     */
    public function __invoke(Request $request, string $id): View
    {
        $group = $this->service->get($id);
        if (!$group instanceof GroupInterface) {
            throw new NotFoundHttpException(sprintf('Group with ID "%s" not found', $id));
        }

        $this->service->remove($group);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
