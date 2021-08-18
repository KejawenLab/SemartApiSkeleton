<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Media;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use KejawenLab\ApiSkeleton\Media\MediaService;
use KejawenLab\ApiSkeleton\Media\Model\MediaInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Vich\UploaderBundle\Mapping\PropertyMappingFactory;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Get extends AbstractFOSRestController
{
    public function __construct(private MediaService $service, private PropertyMappingFactory $mapping)
    {
    }

    /**
     * @Rest\Get("/medias/{path}", name=Get::class, requirements={"path"=".+"})
     *
     * @Cache(expires="+2 week", public=true)
     *
     * @OA\Tag(name="Media")
     * @OA\Response(
     *     response=200,
     *     description= "Api client detail",
     *     content={
     *         @OA\MediaType(
     *             mediaType="*",
     *             @OA\Schema(
     *                 type="string",
     *                 format="binary"
     *             )
     *         )
     *     }
     * )
     *
     * @Security(name="Bearer")
     */
    public function __invoke(Request $request, string $path): Response
    {
        $path = explode('/', $path);
        if (MediaInterface::PUBLIC_FIELD === $path[0]) {
            array_shift($path);
        }

        $fileName = implode('/', $path);
        $media = $this->service->getByFile($fileName);
        if (!$media) {
            throw new NotFoundHttpException(sprintf('File "%s" not found', $fileName));
        }

        if (!$this->getUser() && !$media->isPublic()) {
            throw new NotFoundHttpException(sprintf('File "%s" not found', $fileName));
        }

        $file = new File(sprintf('%s%s%s%s%s',
            $this->mapping->fromField($media, 'file')->getUploadDestination(),
            DIRECTORY_SEPARATOR,
            $media->getFolder(),
            DIRECTORY_SEPARATOR,
            $media->getFileName()
        ));

        $response = new BinaryFileResponse($file->getRealPath());
        $response->setPrivate();
        if ($request->query->get('f')) {
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $file->getFilename());
        }

        return $response;
    }
}
