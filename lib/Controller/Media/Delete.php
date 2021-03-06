<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Media;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Media\MediaService;
use KejawenLab\ApiSkeleton\Media\Model\MediaInterface;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Permission(menu="MEDIA", actions={Permission::DELETE})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Delete extends AbstractFOSRestController
{
    public function __construct(private MediaService $service)
    {
    }

    /**
     * @Rest\Delete("/medias/{id}", name=Delete::class)
     *
     * @OA\Tag(name="Media")
     * @OA\Response(
     *     response=204,
     *     description="Delete media"
     * )
     *
     * @Security(name="Bearer")
     */
    public function __invoke(Request $request, string $id): View
    {
        $media = $this->service->get($id);
        if (!$media instanceof MediaInterface) {
            throw new NotFoundHttpException(sprintf('Media ID: "%s" not found', $id));
        }

        $this->service->remove($media);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
