<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Me;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\ApiClient\ApiClientService;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\Entity\ApiClient;
use KejawenLab\ApiSkeleton\Form\ApiClientType;
use KejawenLab\ApiSkeleton\Form\FormFactory;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use KejawenLab\ApiSkeleton\Security\User;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'PROFILE', actions: [Permission::ADD])]
#[Tag(name: 'Profile')]
final class CreateApiClient extends AbstractFOSRestController
{
    public function __construct(
        private readonly FormFactory $formFactory,
        private readonly UserProviderFactory $userProviderFactory,
        private readonly ApiClientService $service,
        private readonly TranslatorInterface $translator,
        private readonly SettingService $setting,
    ) {
    }

    #[Post(data: '/me/api-clients', name: CreateApiClient::class)]
    #[Security(name: 'Bearer')]
    #[RequestBody(
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(ref: new Model(type: ApiClientType::class), type: 'object'),
        ),
    )]
    #[OA\Response(
        response: 200,
        description: 'Create api client for logged user',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(ref: new Model(type: ApiClient::class, groups: ['read']), type: 'object'),
        ),
    )]
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

        if ($this->service->countByUser($user) >= $this->setting->getMaxApiPerUser()) {
            throw new TooManyRequestsHttpException($this->translator->trans('sas.page.api_client.max_api_client_reached', [], 'pages'));
        }

        $form = $this->formFactory->submitRequest(ApiClientType::class, $request);
        if (!$form->isValid()) {
            return $this->view((array) $form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        /** @var ApiClientInterface $client */
        $client = $form->getData();
        $client->setUser($user);
        $this->service->save($client);

        return $this->view($this->service->get($client->getId()), Response::HTTP_CREATED);
    }
}
