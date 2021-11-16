<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\ApiClient;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\ApiClient\ApiClientService;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Permission(menu="APICLIENT", actions={Permission::DELETE})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Delete extends AbstractFOSRestController
{
    public function __construct(
        private ApiClientService $service,
        private UserService $userService,
        private TranslatorInterface $translator,
    ) {
    }

    /**
     * @OA\Tag(name="Api Client")
     * @OA\Response(
     *     response=204,
     *     description="Api client deletec"
     * )
     * @Security(name="Bearer")
     */
    #[\FOS\RestBundle\Controller\Annotations\Delete(data: '/users/{userId}/api-clients/{id}', name: Delete::class)]
    public function __invoke(string $userId, string $id) : View
    {
        $user = $this->userService->get($userId);
        if (!$user instanceof UserInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.user.not_found', [], 'pages'));
        }

        $client = $this->service->get($id);
        if (!$client instanceof ApiClientInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.api_client.not_found', [], 'pages'));
        }

        $this->service->remove($client);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
