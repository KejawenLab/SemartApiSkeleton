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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Permission(menu="MEDIA", actions={Permission::DELETE})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Delete extends AbstractFOSRestController
{
    public function __construct(private MediaService $service, private TranslatorInterface $translator)
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
    public function __invoke(string $id): View
    {
        $media = $this->service->get($id);
        if (!$media instanceof MediaInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.media.not_found', [], 'pages'));
        }

        $this->service->remove($media);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
