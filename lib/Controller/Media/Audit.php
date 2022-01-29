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
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'AUDIT', actions: [Permission::VIEW])]
#[Tag(name: 'Media')]
final class Audit extends AbstractFOSRestController
{
    public function __construct(private readonly MediaService $service, private readonly AuditService $audit, private readonly Reader $reader)
    {
    }

    #[Get(data: '/medias/{id}/audit', name: Audit::class, priority: -255)]
    #[Security(name: 'Bearer')]
    #[Response(
        response: 200,
        description: 'Audit list',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'array',
                items: new OA\Items(
                    properties: [
                        new OA\Property(
                            property: 'entity',
                            ref: new Model(type: Media::class, groups: ['read']),
                            type: 'object',
                        ),
                        new OA\Property(property: 'type', type: 'string'),
                        new OA\Property(property: 'user_id', type: 'string'),
                        new OA\Property(property: 'username', type: 'string'),
                        new OA\Property(property: 'ip_address', type: 'string'),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'new', type: 'string'),
                                    new OA\Property(property: 'old', type: 'string'),
                                ],
                            ),
                        ),
                    ],
                ),
            ),
        ),
    )]
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
