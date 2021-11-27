<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\User;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Delete as Route;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Permission(menu="USER", actions={Permission::DELETE})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Delete extends AbstractFOSRestController
{
    public function __construct(private UserService $service, private TranslatorInterface $translator)
    {
    }

    /**
     * @OA\Tag(name="User")
     * @OA\Response(
     *     response=204,
     *     description="Delete user"
     * )
     * @Security(name="Bearer")
     */
    #[Route(data: '/users/{id}', name: Delete::class)]
    public function __invoke(string $id): View
    {
        $user = $this->service->get($id);
        if (!$user instanceof UserInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.user.not_found', [], 'pages'));
        }

        $this->service->remove($user);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
