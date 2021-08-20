<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\ApiClient;

use KejawenLab\ApiSkeleton\Admin\Controller\AbstractController;
use KejawenLab\ApiSkeleton\Admin\Controller\User\Main as GetAllUser;
use KejawenLab\ApiSkeleton\ApiClient\ApiClientService;
use KejawenLab\ApiSkeleton\Entity\ApiClient;
use KejawenLab\ApiSkeleton\Form\ApiClientType;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use KejawenLab\ApiSkeleton\Security\User;
use ReflectionClass;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="APICLIENT", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Main extends AbstractController
{
    public function __construct(
        private ApiClientService $service,
        private UserService $userService,
        private UserProviderFactory $userProviderFactory,
        Paginator $paginator,
    ) {
        parent::__construct($this->service, $paginator);
    }

    /**
     * @Route("/users/{userId}/api-clients", name=Main::class, methods={"GET", "POST"}, defaults={"userId": "2e0cac45-822f-4b97-95f1-9516ad824ec1"})
     */
    public function __invoke(Request $request, string $userId): Response
    {
        $user = $this->userService->get($userId);
        if (!$user instanceof User) {
            $this->addFlash('error', 'sas.page.user.not_found');

            return new RedirectResponse($this->generateUrl(GetAllUser::class));
        }

        $user = $this->userProviderFactory->getRealUser($user);
        if (!$user instanceof UserInterface) {
            $this->addFlash('error', 'sas.page.user.not_found');

            return new RedirectResponse($this->generateUrl(GetAllUser::class));
        }

        $client = new ApiClient();
        $client->setUser($user);
        $form = $this->createForm(ApiClientType::class, $client);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->service->save($client);

                $this->addFlash('info', 'sas.page.api_client.saved');
            }
        }

        return $this->renderList($form, $request, new ReflectionClass(ApiClient::class));
    }
}
