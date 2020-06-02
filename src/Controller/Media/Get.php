<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Media;

use Alpabit\ApiSkeleton\Media\MediaService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Security;
use Psr\Log\LoggerInterface;
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
    private $service;

    private $mapping;

    private $logger;

    public function __construct(MediaService $service, PropertyMappingFactory $mapping, LoggerInterface $auditLogger)
    {
        $this->service = $service;
        $this->mapping = $mapping;
        $this->logger = $auditLogger;
    }
    /**
     * @Rest\Get("/medias/{path}", requirements={"path"=".+"})
     *
     * @SWG\Tag(name="Media")
     * @SWG\Get(produces={"image/png", "image/gif", "image/jpg", "image/jpeg"})
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
        $this->logger->info(sprintf('[%s][%s][%s]', __CLASS__, $path, serialize($request->query->all())));

        $path = explode('/', $path);
        $fileName = array_pop($path);
        $media = $this->service->getByFile($fileName);
        if (!$media) {
            throw new NotFoundHttpException(sprintf('File "%s" not found', $fileName));
        }

        if (!$this->getUser() && !$media->isPublic()) {
            throw new NotFoundHttpException(sprintf('File "%s" not found', $fileName));
        }

        $response = new Response();
        $file = new File(sprintf('%s/%s', $this->mapping->fromField($media, 'file')->getUploadDestination(), $media->getFileName()));

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
