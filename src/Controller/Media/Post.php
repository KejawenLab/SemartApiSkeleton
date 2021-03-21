<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Media;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\Media;
use KejawenLab\ApiSkeleton\Form\FormFactory;
use KejawenLab\ApiSkeleton\Media\MediaService;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Noxlogic\RateLimitBundle\Annotation\RateLimit;
use OpenApi\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Permission(menu="MEDIA", actions={Permission::ADD})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
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
     * @RateLimit(limit=7, period=1)
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
