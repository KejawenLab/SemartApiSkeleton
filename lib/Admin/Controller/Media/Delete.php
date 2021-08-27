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
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Delete extends AbstractController
{
    public function __construct(private MediaService $service)
    {
    }

    #[Route(path: '/medias/{id}/delete', name: Delete::class, methods: ['GET'])]
    public function __invoke(string $id): Response
    {
        $media = $this->service->get($id);
        if (!$media instanceof MediaInterface) {
            $this->addFlash('error', 'sas.page.media.not_found');

            return new RedirectResponse($this->generateUrl(Main::class));
        }
        $this->service->remove($media);
        $this->addFlash('info', 'sas.page.media.deleted');

        return new RedirectResponse($this->generateUrl(Main::class));
    }
}
