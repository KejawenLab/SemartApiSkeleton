<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Media;

use KejawenLab\ApiSkeleton\Media\MediaService;
use KejawenLab\ApiSkeleton\Media\Model\MediaInterface;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\SemartApiSkeleton;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Mapping\PropertyMappingFactory;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'MEDIA', actions: [Permission::VIEW])]
final class Get extends AbstractController
{
    public function __construct(private readonly MediaService $service, private readonly PropertyMappingFactory $mapping)
    {
    }

    #[Route(path: '/medias/{path}', name: self::class, requirements: ['path' => '.+'], methods: ['GET'])]
    public function __invoke(Request $request, string $path): Response
    {
        $path = explode('/', $path);
        if (MediaInterface::PUBLIC_FIELD === $path[0]) {
            array_shift($path);
        }

        $fileName = implode('/', $path);
        $media = $this->service->getByFile($fileName);
        if (!$media instanceof MediaInterface) {
            $this->addFlash('error', 'sas.page.media.not_found');

            return new RedirectResponse($this->generateUrl(Main::class));
        }

        $file = new File(sprintf(
            '%s%s%s%s%s',
            $this->mapping->fromField($media, 'file')->getUploadDestination(),
            \DIRECTORY_SEPARATOR,
            $media->getFolder(),
            \DIRECTORY_SEPARATOR,
            $media->getFileName()
        ));

        $response = new BinaryFileResponse($file->getRealPath());
        $response->setPrivate();
        $response->setMaxAge(SemartApiSkeleton::PAGE_CACHE_LIFETIME);

        if ($request->query->get('f')) {
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $file->getFilename());
        }

        return $response;
    }
}
