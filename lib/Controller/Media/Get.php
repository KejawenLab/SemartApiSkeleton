<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Media;

use FOS\RestBundle\Controller\AbstractFOSRestController;
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
use Symfony\Contracts\Translation\TranslatorInterface;
use Vich\UploaderBundle\Mapping\PropertyMappingFactory;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Get extends AbstractFOSRestController
{
    public function __construct(
        private MediaService $service,
        private PropertyMappingFactory $mapping,
        private TranslatorInterface $translator,
    ) {
    }

    /**
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
    #[\FOS\RestBundle\Controller\Annotations\Get(data: '/medias/{path}', name: Get::class, requirements: ['path' => '.+'])]
    public function __invoke(Request $request, string $path) : Response
    {
        $path = explode('/', $path);
        if (MediaInterface::PUBLIC_FIELD === $path[0]) {
            array_shift($path);
        }
        $fileName = implode('/', $path);
        $media = $this->service->getByFile($fileName);
        if (!$media instanceof MediaInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.media.not_found', [], 'pages'));
        }
        if (!$this->getUser() && !$media->isPublic()) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.media.not_found', [], 'pages'));
        }
        $file = new File(sprintf(
            '%s%s%s%s%s',
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
