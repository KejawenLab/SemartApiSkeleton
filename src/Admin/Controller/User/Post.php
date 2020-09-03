<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\User;

use KejawenLab\ApiSkeleton\Entity\User;
use KejawenLab\ApiSkeleton\Form\UserType;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="USER", actions={Permission::ADD})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Post extends AbstractController
{
    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/users/add", methods={"GET", "POST"}, priority=1)
     */
    public function __invoke(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->service->save($user);

                $this->addFlash('info', 'sas.page.user.saved');

                return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_user_getall__invoke'));
            }
        }

        return $this->render('user/form.html.twig', [
            'page_title' => 'sas.page.user.add',
            'form' => $form->createView(),
        ]);
    }
}
