<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\User;

use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="USER", actions={Permission::DELETE})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Delete extends AbstractController
{
    public function __construct(private UserService $service)
    {
    }

    /**
     * @Route(path="/users/{id}/delete", name=Delete::class, methods={"GET"})
     */
    public function __invoke(string $id): Response
    {
        $user = $this->service->get($id);
        if (!$user instanceof UserInterface) {
            $this->addFlash('error', 'sas.page.user.not_found');

            return new RedirectResponse($this->generateUrl(Main::class));
        }

        $this->service->remove($user);

        $this->addFlash('info', 'sas.page.user.deleted');

        return new RedirectResponse($this->generateUrl(Main::class));
    }
}
