<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Setting;

use OpenApi\Attributes\Tag;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\Setting;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class PublicAllSetting extends AbstractFOSRestController
{
    public function __construct(private readonly SettingService $service, private readonly Paginator $paginator)
    {
    }

    #[Get(data: '/settings/public', name: PublicAllSetting::class, priority: 1)]
    #[Tag(name: 'Setting')]
    #[Parameter(name: 'page', in: 'query', new OA\Schema(type: 'integer', format: 'int32'))]
    #[Parameter(name: 'limit', in: 'query', new OA\Schema(type: 'integer', format: 'int32'))]
    #[Parameter(name: 'q', in: 'query', new OA\Schema(type: 'string'))]
    #[Parameter(name: 'parameter', in: 'query', new OA\Schema(type: 'string'))]
    #[Parameter(name: 'group', in: 'query', new OA\Schema(type: 'string'))]
    #[Response(response: 200, description: 'Setting list', content: [new OA\MediaType(mediaType: 'application/json', new OA\Schema(type: 'array', new OA\Items(ref: new Model(type: Setting::class, groups: ['read']))))])]
    public function __invoke(Request $request): View
    {
        return $this->view($this->paginator->paginate($this->service->getQueryBuilder(), $request, Setting::class));
    }
}
