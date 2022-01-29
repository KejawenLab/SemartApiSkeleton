<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\ApiClient;

use OpenApi\Attributes\Tag;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\ApiClient\ApiClientService;
use KejawenLab\ApiSkeleton\Entity\ApiClient;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'APICLIENT', actions: [Permission::VIEW])]
#[Tag(name: 'Api Client')]
final class GetAll extends AbstractFOSRestController
{
    public function __construct(
        private readonly ApiClientService $service,
        private readonly UserService $userService,
        private readonly Paginator $paginator,
        private readonly TranslatorInterface $translator,
    ) {
    }

    #[Get(data: 'users/{userId}/api-clients', name: GetAll::class, defaults: ['userId' => '2e0cac45-822f-4b97-95f1-9516ad824ec1'])]
    #[Security(name: 'Bearer')]
    #[Parameter(name: 'page', in: 'query', schema: new OA\Schema(type: 'integer', format: 'int32'))]
    #[Parameter(name: 'limit', in: 'query', schema: new OA\Schema(type: 'integer', format: 'int32'))]
    #[Response(
        response: 200,
        description: 'Api client list',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'array',
                items: new OA\Items(ref: new Model(type: ApiClient::class, groups: ['read'])),
            ),
        ),
    )]
    public function __invoke(Request $request, string $userId): View
    {
        $user = $this->userService->get($userId);
        if (!$user instanceof UserInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.user.not_found', [], 'pages'));
        }

        return $this->view($this->paginator->paginate($this->service->getQueryBuilder(), $request, ApiClient::class));
    }
}
