<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Group;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Delete as Route;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Security\Service\GroupService;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Permission(menu="GROUP", actions={Permission::DELETE})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Delete extends AbstractFOSRestController
{
    public function __construct(private readonly GroupService $service, private readonly TranslatorInterface $translator)
    {
    }

    /**
     * @OA\Tag(name="Group")
     * @OA\Response(
     *     response=204,
     *     description="Delete group"
     * )
     * @Security(name="Bearer")
     */
    #[Route(data: '/groups/{id}', name: Delete::class)]
    public function __invoke(string $id): View
    {
        $group = $this->service->get($id);
        if (!$group instanceof GroupInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.group.not_found', [], 'pages'));
        }

        if (GroupInterface::SUPER_ADMIN_ID === $group->getId()) {
            throw new UnauthorizedHttpException($this->translator->trans('sas.page.error.unauthorized', [], 'pages'));
        }

        $this->service->remove($group);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
