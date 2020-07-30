<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Group;

use KejawenLab\ApiSkeleton\Media\MediaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Delete extends AbstractController
{
    private MediaService $service;

    public function __construct(MediaService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/groups/{id}/delete", methods={"GET"})
     */
    public function __invoke(Request $request, string $id)
    {
        if (!$group = $this->service->get($id)) {
            $this->addFlash('error', 'sas.page.group.not_found');

            return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_group_getall__invoke'));
        }

        $this->service->remove($group);

        $this->addFlash('info', 'sas.page.group.deleted');

        return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_group_getall__invoke'));
    }
}
