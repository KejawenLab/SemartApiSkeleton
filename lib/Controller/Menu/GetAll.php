<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Menu;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\Menu;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Service\MenuService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'MENU', actions: [Permission::VIEW])]
#[Tag(name: 'Menu')]
final class GetAll extends AbstractFOSRestController
{
    public function __construct(private readonly MenuService $service, private readonly Paginator $paginator)
    {
    }

    #[Get(data: '/menus', name: GetAll::class)]
    #[Security(name: 'Bearer')]
    #[Parameter(name: 'page', in: 'query', schema: new OA\Schema(type: 'integer', format: 'int32'))]
    #[Parameter(name: 'limit', in: 'query', schema: new OA\Schema(type: 'integer', format: 'int32'))]
    #[Parameter(name: 'q', in: 'query', schema: new OA\Schema(type: 'string'))]
    #[Parameter(name: 'code', description: 'Filter menu by code', in: 'query', schema: new OA\Schema(type: 'string'))]
    #[Response(
        response: 200,
        description: 'Menu list',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'array',
                items: new OA\Items(ref: new Model(type: Menu::class, groups: ['read'])),
            ),
        ),
    )]
    public function __invoke(Request $request): View
    {
        return $this->view($this->paginator->paginate($this->service->getQueryBuilder(), $request, Menu::class));
    }
}
