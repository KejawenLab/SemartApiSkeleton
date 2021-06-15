<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Media;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\Media;
use KejawenLab\ApiSkeleton\Form\FormFactory;
use KejawenLab\ApiSkeleton\Form\MediaType;
use KejawenLab\ApiSkeleton\Media\MediaService;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Permission(menu="MEDIA", actions={Permission::ADD})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Post extends AbstractFOSRestController
{
    public function __construct(private FormFactory $formFactory, private MediaService $service)
    {
    }

    /**
     * @Rest\Post("/medias")
     *
     * @OA\Tag(name="Media")
     * @OA\RequestBody(
     *     content={
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 ref=@Model(type=MediaType::class)
     *             )
     *         )
     *     }
     * )
     * @OA\Response(
     *     response=201,
     *     description= "Media created",
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 ref=@Model(type=Media::class, groups={"read"})
     *             )
     *         )
     *     }
     * )
     *
     * @Security(name="Bearer")
     */
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
