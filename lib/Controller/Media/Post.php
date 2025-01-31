<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Media;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Post as Route;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\Media;
use KejawenLab\ApiSkeleton\Form\MediaType;
use KejawenLab\ApiSkeleton\Media\MediaService;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'MEDIA', actions: [Permission::ADD])]
#[Tag(name: 'Media')]
final class Post extends AbstractFOSRestController
{
    public function __construct(private readonly MediaService $service)
    {
    }

    #[Route(data: '/medias', name: self::class)]
    #[Security(name: 'Bearer')]
    #[RequestBody(
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(ref: new Model(type: MediaType::class), type: 'object'),
        ),
    )]
    #[OA\Response(
        response: 201,
        description: 'Media created',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(ref: new Model(type: Media::class, groups: ['read']), type: 'object'),
        ),
    )]
    public function __invoke(Request $request): View
    {
        $media = new Media();
        if (!$file = $request->files->get('file')) {
            return $this->view(['file' => 'This property can not be blank']);
        }

        $public = $request->request->get('public', true);
        if ('false' === $public || 0 === $public) {
            $public = false;
        }

        $media->setFile($file);
        $media->setPublic((bool) $public);
        $media->setFolder($request->request->get('folder'));
        $this->service->save($media);

        return $this->view($this->service->get($media->getId()), Response::HTTP_CREATED);
    }
}
