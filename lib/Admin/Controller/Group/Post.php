<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Group;

use KejawenLab\ApiSkeleton\Entity\Group;
use KejawenLab\ApiSkeleton\Form\GroupType;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Service\GroupService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="GROUP", actions={Permission::ADD})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Post extends AbstractController
{
    public function __construct(private GroupService $service)
    {
    }

    /**
     * @Route("/groups/add", name=Post::class, methods={"GET", "POST"}, priority=1)
     */
    public function __invoke(Request $request): Response
    {
        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->service->save($group);

                $this->addFlash('info', 'sas.page.group.saved');

                return new RedirectResponse($this->generateUrl(GetAll::class));
            }
        }

        return $this->render('group/form.html.twig', [
            'page_title' => 'sas.page.group.add',
            'form' => $form->createView(),
        ]);
    }
}
