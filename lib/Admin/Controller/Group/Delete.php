<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Group;

use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Security\Service\GroupService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'GROUP', actions: [Permission::DELETE])]
final class Delete extends AbstractController
{
    public function __construct(private readonly GroupService $service)
    {
    }

    #[Route(path: '/groups/{id}/delete', name: self::class, methods: ['GET'])]
    public function __invoke(string $id): Response
    {
        $group = $this->service->get($id);
        if (!$group instanceof GroupInterface) {
            $this->addFlash('error', 'sas.page.group.not_found');

            return new RedirectResponse($this->generateUrl(Main::class));
        }

        if (GroupInterface::SUPER_ADMIN_ID === $group->getId()) {
            $this->addFlash('error', 'sas.page.error.unauthorized');

            return new RedirectResponse($this->generateUrl(Main::class));
        }

        $this->service->remove($group);

        $this->addFlash('info', 'sas.page.group.deleted');

        return new RedirectResponse($this->generateUrl(Main::class));
    }
}
