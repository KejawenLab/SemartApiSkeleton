<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\User;

use KejawenLab\ApiSkeleton\Admin\Controller\AbstractController;
use KejawenLab\ApiSkeleton\Entity\User;
use KejawenLab\ApiSkeleton\Form\UserType;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="USER", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Main extends AbstractController
{
    public function __construct(private UserService $service, Paginator $paginator)
    {
        parent::__construct($this->service, $paginator);
    }

    /**
     * @Route("/users", name=Main::class, methods={"GET", "POST"})
     */
    public function __invoke(Request $request): Response
    {
        $user = new User();
        $flashs = $request->getSession()->getFlashBag()->get('id');
        foreach ($flashs as $flash) {
            $user = $this->service->get($flash);
            if ($user) {
                $this->addFlash('id', $user->getId());

                break;
            }
        }

        $form = $this->createForm(UserType::class, $user);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->service->save($user);
                $this->addFlash('info', 'sas.page.user.saved');
            }
        }

        return $this->renderList($form, $request, new ReflectionClass(User::class));
    }
}
