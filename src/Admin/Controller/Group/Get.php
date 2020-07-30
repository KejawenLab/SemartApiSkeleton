<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Group;

use KejawenLab\ApiSkeleton\Security\Service\GroupService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Mapping\PropertyMappingFactory;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Get extends AbstractController
{
    private GroupService $service;

    private PropertyMappingFactory $mapping;

    public function __construct(GroupService $service, PropertyMappingFactory $mapping)
    {
        $this->service = $service;
        $this->mapping = $mapping;
    }

    /**
     * @Route("/groups/{id}", methods={"GET"})
     */
    public function __invoke(Request $request, string $id)
    {
        if (!$group = $this->service->get($id)) {
            $this->addFlash('error', 'sas.page.group.not_found');

            return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_group_getall__invoke'));
        }

        return $this->render('group/view.html.twig', [
            'page_title' => 'sas.page.group.view',
            'data' => $group,
        ]);
    }
}
