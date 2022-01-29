<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Menu;

use OpenApi\Attributes\Tag;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\Menu;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Service\MenuService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'MENU', actions: [Permission::VIEW])]
final class GetAll extends AbstractFOSRestController
{
    public function __construct(private readonly MenuService $service, private readonly Paginator $paginator)
    {
    }
    #[Get(data: '/menus', name: GetAll::class)]
    #[Security(name: 'Bearer')]
    #[Tag(name: 'Menu')]
    #[Parameter(name: 'page', in: 'query', new OA\Schema(type: 'integer', format: 'int32'))]
    #[Parameter(name: 'limit', in: 'query', new OA\Schema(type: 'integer', format: 'int32'))]
    #[Parameter(name: 'q', in: 'query', new OA\Schema(type: 'string'))]
    #[Parameter(name: 'code', in: 'query', new OA\Schema(type: 'string'), description: 'Filter menu by code')]
    #[Response(response: 200, description: 'Menu list', content: [new OA\MediaType(mediaType: 'application/json', new OA\Schema(type: 'array', new OA\Items(ref: new Model(type: Menu::class, groups: ['read']))))])]
    public function __invoke(Request $request): View
    {
        return $this->view($this->paginator->paginate($this->service->getQueryBuilder(), $request, Menu::class));
    }
}
