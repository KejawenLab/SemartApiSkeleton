<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Me;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\ApiClient\ApiClientService;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use KejawenLab\ApiSkeleton\Security\User;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'PROFILE', actions: [Permission::DELETE])]
final class DeleteApiClient extends AbstractFOSRestController
{
    public function __construct(
        private readonly UserProviderFactory $userProviderFactory,
        private readonly ApiClientService $service,
        private readonly TranslatorInterface $translator
    ) {
    }
    #[Delete(data: '/me/api-clients/{id}', name: DeleteApiClient::class)]
    #[Security(name: 'Bearer')]
    #[Tag(name: 'Profile')]
    #[\OpenApi\Attributes\Response(response: 204, description: 'Delete api client related to logged user')]
    public function __invoke(string $id): View
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.user.not_found', [], 'pages'));
        }

        $user = $this->userProviderFactory->getRealUser($user);
        if (!$user instanceof UserInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.user.not_found', [], 'pages'));
        }

        $client = $this->service->getByIdAndUser($id, $user);
        if (!$client instanceof ApiClientInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.api_client.not_found', [], 'pages'));
        }

        $this->service->remove($client);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
