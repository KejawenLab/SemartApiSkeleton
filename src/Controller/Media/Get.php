<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Media;

use Alpabit\ApiSkeleton\Media\MediaService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Vich\UploaderBundle\Mapping\PropertyMappingFactory;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Get extends AbstractFOSRestController
{
    private MediaService $service;

    private PropertyMappingFactory $mapping;

    public function __construct(MediaService $service, PropertyMappingFactory $mapping)
    {
        $this->service = $service;
        $this->mapping = $mapping;
    }
    /**
     * @Rest\Get("/medias/{path}", requirements={"path"=".+"})
     *
     * @Cache(expires="+2 week", public=true)
     *
     * @SWG\Tag(name="Media")
     * @SWG\Get(produces={
     *     "image/png",
     *     "image/gif",
     *     "image/jpeg",
     *     "application/pdf",
     *     "application/zip",
     *     "application/gzip",
     *     "application/vnd.rar",
     *     "application/x-tar",
     *     "application/x-7z-compressed",
     *     "audio/mpeg",
     *     "video/mpeg",
     *     "application/vnd.openxmlformats-officedocument.presentationml.presentation",
     *     "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
     *     "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
     *     "text/csv",
     *     "text/plain"
     * })
     * @SWG\Response(
     *     response=200,
     *     description="Return file",
     *     @SWG\Schema(
     *          type="file",
     *          format="binary"
     *     )
     * )
     * @Security(name="Bearer")
     *
     * @param Request $request
     * @param string $path
     *
     * @return Response
     */
    public function __invoke(Request $request, string $path): Response
    {
        $path = explode('/', $path);
        array_shift($path);
        $fileName = implode('/', $path);
        $media = $this->service->getByFile($fileName);
        if (!$media) {
            throw new NotFoundHttpException(sprintf('File "%s" not found', $fileName));
        }

        if (!$this->getUser() && !$media->isPublic()) {
            throw new NotFoundHttpException(sprintf('File "%s" not found', $fileName));
        }

        $response = new Response();
        $file = new File(sprintf('%s%s%s%s%s', $this->mapping->fromField($media, 'file')->getUploadDestination(), DIRECTORY_SEPARATOR, $media->getFolder(), DIRECTORY_SEPARATOR, $media->getFileName()));

        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', (string) $file->getMimeType());
        $response->headers->set('Content-length', (string) $file->getSize());

        if ($request->query->get('f')) {
            $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s.%s"', $file->getFilename(), $file->getExtension()));
        }

        $response->setContent(file_get_contents($file->getRealPath()));

        return $response;
    }
}
