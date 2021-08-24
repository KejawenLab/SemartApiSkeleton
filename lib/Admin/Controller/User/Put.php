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
 * @Permission(menu="USER", actions={Permission::EDIT})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Put extends AbstractController
{
    public function __construct(private UserService $service)
    {
    }

    /**
     * @Route(path="/users/{id}/edit", name=Put::class, methods={"GET"}, priority=1)
     */
    public function __invoke(string $id): Response
    {
        $user = $this->service->get($id);
        if (!$user instanceof UserInterface) {
            $this->addFlash('error', 'sas.page.user.not_found');

            return new RedirectResponse($this->generateUrl(Main::class));
        }

        $this->addFlash('id', $user->getId());

        return new RedirectResponse($this->generateUrl(Main::class));
    }
}
