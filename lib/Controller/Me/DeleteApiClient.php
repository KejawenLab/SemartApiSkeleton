<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Me;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\ApiClient\ApiClientService;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use KejawenLab\ApiSkeleton\Security\User;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Permission(menu="PROFILE", actions={Permission::DELETE})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class DeleteApiClient extends AbstractFOSRestController
{
    public function __construct(private UserProviderFactory $userProviderFactory, private ApiClientService $service, private TranslatorInterface $translator)
    {
    }

    /**
     * @Rest\Delete("/me/api-clients/{id}", name=DeleteApiClient::class)
     *
     * @OA\Tag(name="Profile")
     * @OA\Response(
     *     response=204,
     *     description="Delete api client related to logged user"
     * )
     *
     * @Security(name="Bearer")
     */
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
