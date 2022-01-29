<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Cron;

use OpenApi\Attributes\Tag;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Cron\CronService;
use KejawenLab\ApiSkeleton\Entity\Cron;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'CRON', actions: [Permission::VIEW])]
final class GetAll extends AbstractFOSRestController
{
    public function __construct(private readonly CronService $service, private readonly Paginator $paginator)
    {
    }
    #[Get(data: '/cronjobs', name: GetAll::class, priority: -7)]
    #[Security(name: 'Bearer')]
    #[Tag(name: 'Cron')]
    #[Parameter(name: 'page', in: 'query', new OA\Schema(type: 'integer', format: 'int32'))]
    #[Parameter(name: 'limit', in: 'query', new OA\Schema(type: 'integer', format: 'int32'))]
    #[Parameter(name: 'q', in: 'query', new OA\Schema(type: 'string'))]
    #[Response(response: 200, description: 'Api client list', content: [new OA\MediaType(mediaType: 'application/json', new OA\Schema(type: 'array', new OA\Items(ref: new Model(type: Cron::class, groups: ['read']))))])]
    public function __invoke(Request $request): View
    {
        return $this->view($this->paginator->paginate($this->service->getQueryBuilder(), $request, Cron::class));
    }
}
