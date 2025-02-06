<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\ApiClient;

use KejawenLab\ApiSkeleton\Admin\Controller\User\Main as GetAllUser;
use KejawenLab\ApiSkeleton\ApiClient\ApiClientService;
use KejawenLab\ApiSkeleton\Entity\ApiClient;
use KejawenLab\ApiSkeleton\Form\ApiClientType;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'APICLIENT', actions: [Permission::VIEW])]
final class Main extends AbstractController
{
    public function __construct(
        private readonly ApiClientService $service,
        private readonly UserService      $userService,
        private readonly Paginator        $paginator,
    )
    {
    }

    #[Route(path: '/users/{userId}/api-clients', name: self::class, defaults: ['userId' => '2e0cac45-822f-4b97-95f1-9516ad824ec1'], methods: ['GET', 'POST'])]
    public function __invoke(Request $request, string $userId): Response
    {
        $user = $this->userService->get($userId);
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
                $this->service->save($form->getData());

                $this->addFlash('info', 'sas.page.api_client.saved');
            }
        }

        $class = new ReflectionClass(ApiClient::class);
        $context = StringUtil::lowercase($class->getShortName());

        return $this->render(sprintf('%s/all.html.twig', $context), [
            'page_title' => 'sas.page.api_client.list',
            'context' => $context,
            'properties' => $class->getProperties(ReflectionProperty::IS_PRIVATE),
            'paginator' => $this->paginator->paginate($this->service->getQueryBuilder(), $request, ApiClient::class),
            'form' => $this->createForm(ApiClientType::class)->createView(),
            'user_id' => $userId,
        ]);
    }
}
