<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\ApiClient;

use OpenApi\Attributes\Tag;
use OpenApi\Attributes\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\ApiClient\ApiClientRequestService;
use KejawenLab\ApiSkeleton\Entity\ApiClientRequest;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'APICLIENT', actions: [Permission::VIEW])]
final class Report extends AbstractFOSRestController
{
    public function __construct(
        private readonly ApiClientRequestService $service,
        private readonly UserService $userService,
        private readonly Paginator $paginator,
        private readonly TranslatorInterface $translator,
    ) {
    }
    #[Get(data: '/users/{userId}/api-clients/{id}/logs', name: Report::class, priority: -27)]
    #[Security(name: 'Bearer')]
    #[Tag(name: 'Api Client')]
    #[Response(response: 200, description: 'Api client request list', content: [new OA\MediaType(mediaType: 'application/json', new OA\Schema(type: 'array', new OA\Items(ref: new Model(type: ApiClientRequest::class, groups: ['read']))))])]
    public function __invoke(Request $request, string $userId, string $id): View
    {
        $user = $this->userService->get($userId);
        if (!$user instanceof UserInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.user.not_found', [], 'pages'));
        }

        return $this->view($this->paginator->paginate($this->service->getQueryBuilder(), $request, ApiClientRequest::class));
    }
}
