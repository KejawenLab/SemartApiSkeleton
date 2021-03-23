<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Media;

use KejawenLab\ApiSkeleton\Media\MediaService;
use KejawenLab\ApiSkeleton\Media\Model\MediaInterface;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="MEDIA", actions={Permission::DELETE})
 *
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
     * @Route("/medias/{id}/delete", methods={"GET"})
     */
    public function __invoke(string $id): Response
    {
        $media = $this->service->get($id);
        if (!$media instanceof MediaInterface) {
            $this->addFlash('error', 'sas.page.media.not_found');

            return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_media_getall__invoke'));
        }

        $this->service->remove($media);

        $this->addFlash('info', 'sas.page.media.deleted');

        return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_media_getall__invoke'));
    }
}
