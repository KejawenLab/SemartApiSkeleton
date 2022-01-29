<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Me;

use OpenApi\Attributes\Tag;
use OpenApi\Attributes\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\ApiClient\ApiClientService;
use KejawenLab\ApiSkeleton\Entity\ApiClient;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use KejawenLab\ApiSkeleton\Security\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'PROFILE', actions: [Permission::ADD])]
final class GetApiClient extends AbstractFOSRestController
{
    public function __construct(
        private readonly Paginator $paginator,
        private readonly UserProviderFactory $userProviderFactory,
        private readonly ApiClientService $service,
        private readonly TranslatorInterface $translator,
    ) {
    }
    #[Get(data: '/me/api-clients', name: GetApiClient::class)]
    #[Security(name: 'Bearer')]
    #[Tag(name: 'Profile')]
    #[Response(response: 200, description: "User's api client list", content: [new OA\MediaType(mediaType: 'application/json', new OA\Schema(type: 'array', new OA\Items(ref: new Model(type: ApiClient::class, groups: ['read']))))])]
    public function __invoke(Request $request): View
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.user.not_found', [], 'pages'));
        }

        $user = $this->userProviderFactory->getRealUser($user);
        if (!$user instanceof UserInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.user.not_found', [], 'pages'));
        }

        return $this->view($this->paginator->paginate($this->service->getQueryBuilder(), $request, ApiClient::class));
    }
}
