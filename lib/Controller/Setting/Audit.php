<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Setting;

use OpenApi\Attributes\Tag;
use OpenApi\Attributes\Response;
use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Audit\AuditService;
use KejawenLab\ApiSkeleton\Entity\Setting;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'AUDIT', actions: [Permission::VIEW])]
final class Audit extends AbstractFOSRestController
{
    public function __construct(private readonly SettingService $service, private readonly AuditService $audit, private readonly Reader $reader)
    {
    }
    #[Get(data: '/settings/{id}/audit', name: Audit::class, priority: -255)]
    #[Security(name: 'Bearer')]
    #[Tag(name: 'Setting')]
    #[Response(response: 200, description: 'Audit list', content: [new OA\MediaType(mediaType: 'application/json', new OA\Schema(type: 'array', new OA\Items(properties: [new OA\Property(property: 'entity', type: 'object', new OA\Schema(type: 'object', ref: new Model(type: Setting::class, groups: ['read']))), new OA\Property(type: 'string', property: 'type'), new OA\Property(type: 'string', property: 'user_id'), new OA\Property(type: 'string', property: 'username'), new OA\Property(type: 'string', property: 'ip_address'), new OA\Property(type: 'array', property: 'data', new OA\Items(new OA\Property(type: 'string', property: 'new'), new OA\Property(type: 'string', property: 'old')))])))])]
    public function __invoke(string $id): View
    {
        if (!$entity = $this->service->get($id)) {
            throw new NotFoundHttpException();
        }

        if (!$this->reader->getProvider()->isAuditable(Setting::class)) {
            return $this->view([]);
        }

        return $this->view($this->audit->getAudits($entity, $id)->toArray());
    }
}
