<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Media;

use Alpabit\ApiSkeleton\Media\MediaService;
use Alpabit\ApiSkeleton\Media\Model\MediaInterface;
use Alpabit\ApiSkeleton\Security\Annotation\Permission;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Security;
use Noxlogic\RateLimitBundle\Annotation\RateLimit;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Permission(menu="MEDIA", actions={Permission::DELETE})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
*/
final class Delete extends AbstractFOSRestController
{
    private MediaService $service;

    public function __construct(MediaService $service)
    {
        $this->service = $service;
    }

    /**
     * @Rest\Delete("/medias/{id}")
     *
     * @SWG\Tag(name="Media")
     * @SWG\Response(
     *     response=204,
     *     description="Delete media"
     * )
     *
     * @Security(name="Bearer")
     *
     * @RateLimit(limit=7, period=1)
     *
     * @param Request $request
     * @param string $id
     *
     * @return View
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
