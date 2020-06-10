<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Media;

use Alpabit\ApiSkeleton\Entity\Media;
use Alpabit\ApiSkeleton\Form\FormFactory;
use Alpabit\ApiSkeleton\Media\MediaService;
use Alpabit\ApiSkeleton\Security\Annotation\Permission;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Permission(menu="MEDIA", actions={Permission::ADD})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
*/
final class Post extends AbstractFOSRestController
{
    private FormFactory $formFactory;

    private MediaService $service;

    public function __construct(FormFactory $formFactory, MediaService $service)
    {
        $this->formFactory = $formFactory;
        $this->service = $service;
    }

    /**
    * @Rest\Post("/medias")
    *
    * @SWG\Tag(name="Media")
    * @SWG\Post(consumes={"multipart/form-data"})
    * @SWG\Parameter(
    *     name="file",
    *     in="formData",
    *     type="file",
    *     description="File to upload"
    * )
     * @SWG\Parameter(
     *     name="folder",
     *     in="formData",
     *     type="string",
     *     description="Folder path"
     * )
     * @SWG\Parameter(
     *     name="public",
     *     in="formData",
     *     type="boolean",
     *     description="Is public"
     * )
    * @SWG\Response(
    *     response=201,
    *     description="Crate new media",
    *     @SWG\Schema(
    *         type="object",
    *         ref=@Model(type=Media::class, groups={"read"})
    *     )
    * )
    *
    * @Security(name="Bearer")
    *
    * @param Request $request
    *
    * @return View
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
