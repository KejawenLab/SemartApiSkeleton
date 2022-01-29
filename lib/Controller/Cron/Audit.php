<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Cron;

use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Audit\AuditService;
use KejawenLab\ApiSkeleton\Cron\CronService;
use KejawenLab\ApiSkeleton\Entity\Cron;
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
#[Tag(name: 'Cron')]
final class Audit extends AbstractFOSRestController
{
    public function __construct(private readonly CronService $service, private readonly AuditService $audit, private readonly Reader $reader)
    {
    }

    #[Get(data: '/cronjobs/{id}/audit', name: Audit::class, priority: -255)]
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
                            ref: new Model(type: Cron::class, groups: ['read']),
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
        if (!$entity = $this->service->get($id)) {
            throw new NotFoundHttpException();
        }

        if (!$this->reader->getProvider()->isAuditable(Cron::class)) {
            return $this->view([]);
        }

        return $this->view($this->audit->getAudits($entity, $id)->toArray());
    }
}
