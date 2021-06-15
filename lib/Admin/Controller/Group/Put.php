<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Group;

use KejawenLab\ApiSkeleton\Form\GroupType;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Security\Service\GroupService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="GROUP", actions={Permission::EDIT})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Put extends AbstractController
{
    public function __construct(private GroupService $service)
    {
    }

    /**
     * @Route("/groups/{id}/edit", methods={"GET", "POST"}, priority=1)
     */
    public function __invoke(Request $request, string $id): Response
    {
        $group = $this->service->get($id);
        if (!$group instanceof GroupInterface) {
            $this->addFlash('error', 'sas.page.group.not_found');

            return new RedirectResponse($this->generateUrl(GetAll::class));
        }

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
            'page_title' => 'sas.page.group.edit',
            'form' => $form->createView(),
        ]);
    }
}
