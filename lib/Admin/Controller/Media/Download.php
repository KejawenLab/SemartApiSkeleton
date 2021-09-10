<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Media;

use KejawenLab\ApiSkeleton\Media\MediaService;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Permission(menu="MEDIA", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Download extends AbstractController
{
    public function __construct(private MediaService $service, private SerializerInterface $serializer)
    {
    }

    #[Route(path: '/medias/download', name: Download::class, methods: ['GET'])]
    public function __invoke(): Response
    {
        $records = $this->service->total();
        if (10000 < $records) {
            $this->addFlash('error', 'sas.page.error.too_many_records');

            return new RedirectResponse($this->generateUrl(Main::class));
        }

        $response = new Response();
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', 'text/csv');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s_%s.csv"', 'medias', date('YmdHis')));

        $response->setContent($this->serializer->serialize($this->service->all(), 'csv', ['groups' => 'read']));

        return $response;
    }
}
