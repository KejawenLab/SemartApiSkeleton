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
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Semart\Permission(menu: 'GROUP', actions: [Semart\Permission::VIEW])]
final class Permission extends AbstractFOSRestController
{
    public function __construct(private readonly PermissionService $service, private readonly Paginator $paginator)
    {
    }
    #[Get(data: '/groups/{id}/permissions', name: Permission::class)]
    #[Security(name: 'Bearer')]
    #[Tag(name: 'Group')]
    #[Parameter(name: 'page', in: 'query', new OA\Schema(type: 'integer', format: 'int32'))]
    #[Parameter(name: 'limit', in: 'query', new OA\Schema(type: 'integer', format: 'int32'))]
    #[Parameter(name: 'q', in: 'query', new OA\Schema(type: 'string'))]
    #[Parameter(name: 'menu', in: 'query', new OA\Schema(type: 'string'), description: 'Filter setting by menu')]
    #[Parameter(name: 'group', in: 'query', new OA\Schema(type: 'string'), description: 'Filter setting by group')]
    #[Response(response: 200, description: 'Permission list', content: [new OA\MediaType(mediaType: 'application/json', new OA\Schema(type: 'array', new OA\Items(ref: new Model(type: Entity::class, groups: ['read']))))])]
    public function __invoke(Request $request): View
    {
        return $this->view($this->paginator->paginate($this->service->getQueryBuilder(), $request, Entity::class));
    }
}
