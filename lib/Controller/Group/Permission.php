<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Group;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\Permission as Entity;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation as Semart;
use KejawenLab\ApiSkeleton\Security\Service\PermissionService;
use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Semart\Permission(menu: 'GROUP', actions: [Semart\Permission::VIEW])]
#[Tag(name: 'Group')]
final class Permission extends AbstractFOSRestController
{
    public function __construct(private readonly PermissionService $service, private readonly Paginator $paginator)
    {
    }

    #[Get(data: '/groups/{id}/permissions', name: self::class)]
    #[Security(name: 'Bearer')]
    #[Parameter(name: 'page', in: 'query', schema: new OA\Schema(type: 'integer', format: 'int32'))]
    #[Parameter(name: 'limit', in: 'query', schema: new OA\Schema(type: 'integer', format: 'int32'))]
    #[Parameter(name: 'q', in: 'query', schema: new OA\Schema(type: 'string'))]
    #[Parameter(name: 'menu', description: 'Filter setting by menu', in: 'query', schema: new OA\Schema(type: 'string'))]
    #[Parameter(name: 'group', description: 'Filter setting by group', in: 'query', schema: new OA\Schema(type: 'string'))]
    #[Response(
        response: 200,
        description: 'Permission list',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'array',
                items: new OA\Items(ref: new Model(type: Entity::class, groups: ['read'])),
            ),
        ),
    )]
    public function __invoke(Request $request): View
    {
        return $this->view($this->paginator->paginate($this->service->getQueryBuilder(), $request, Entity::class));
    }
}
