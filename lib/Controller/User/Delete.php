<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\User;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Permission(menu="USER", actions={Permission::DELETE})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Delete extends AbstractFOSRestController
{
    public function __construct(private UserService $service)
    {
    }

    /**
     * @Rest\Delete("/users/{id}", name=Delete::class)
     *
     * @OA\Tag(name="User")
     * @OA\Response(
     *     response=204,
     *     description="Delete user"
     * )
     *
     * @Security(name="Bearer")
     */
    public function __invoke(Request $request, string $id): View
    {
        $user = $this->service->get($id);
        if (!$user instanceof UserInterface) {
            throw new NotFoundHttpException(sprintf('User with ID "%s" not found', $id));
        }

        $this->service->remove($user);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
