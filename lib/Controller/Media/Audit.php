<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Media;

use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Audit\AuditService;
use KejawenLab\ApiSkeleton\Entity\Media;
use KejawenLab\ApiSkeleton\Media\MediaService;
use KejawenLab\ApiSkeleton\Media\Model\MediaInterface;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Permission(menu="AUDIT", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Audit extends AbstractFOSRestController
{
    public function __construct(private readonly MediaService $service, private readonly AuditService $audit, private readonly Reader $reader)
    {
    }

    /**
     * @OA\Tag(name="Media")
     * @OA\Response(
     *     response=200,
     *     description= "Audit list",
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(
     *                     properties={
     *                         @OA\Property(
     *                             property="entity",
     *                             type="object",
     *                             @OA\Schema(
     *                                 type="object",
     *                                 ref=@Model(type=Media::class, groups={"read"})
     *                             )
     *                         ),
     *                         @OA\Property(type="string", property="type"),
     *                         @OA\Property(type="string", property="user_id"),
     *                         @OA\Property(type="string", property="username"),
     *                         @OA\Property(type="string", property="ip_address"),
     *                         @OA\Property(
     *                             type="array",
     *                             property="data",
     *                             @OA\Items(
     *                                 @OA\Property(type="string", property="new"),
     *                                 @OA\Property(type="string", property="old"),
     *                             )
     *                         )
     *                     }
     *                 )
     *             )
     *         )
     *     }
     * )
     *
     * @Security(name="Bearer")
     */
    #[Get(data: '/medias/{id}/audit', name: Audit::class, priority: -255)]
    public function __invoke(string $id): View
    {
        $entity = $this->service->get($id);
        if (!$entity instanceof MediaInterface) {
            throw new NotFoundHttpException();
        }

        if (!$this->reader->getProvider()->isAuditable(Media::class)) {
            return $this->view([]);
        }

        return $this->view($this->audit->getAudits($entity, $id)->toArray());
    }
}
