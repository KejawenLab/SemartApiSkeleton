<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Me;

use KejawenLab\ApiSkeleton\Admin\AdminContext;
use KejawenLab\ApiSkeleton\ApiClient\ApiClientService;
use KejawenLab\ApiSkeleton\Entity\ApiClient;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use KejawenLab\ApiSkeleton\Security\User;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class CreateApiClient extends AbstractController
{
    public function __construct(private UserProviderFactory $userProviderFactory, private SettingService $setting, private ApiClientService $service)
    {
    }

    #[Route(path: '/me/api-clients', name: CreateApiClient::class, methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return new RedirectResponse($this->generateUrl(AdminContext::ADMIN_ROUTE));
        }

        $user = $this->userProviderFactory->getRealUser($user);
        $name = $request->request->get('name');
        if ('' === $name) {
            $this->addFlash('error', 'sas.page.api_client.name_not_provided');

            return new RedirectResponse($this->generateUrl(Profile::class));
        }

        /** @var UserInterface $user */
        if ($this->service->countByUser($user) >= $this->setting->getMaxApiPerUser()) {
            $this->addFlash('error', 'sas.page.api_client.max_api_client_reached');

            return new RedirectResponse($this->generateUrl(Profile::class));
        }

        $client = new ApiClient();
        $client->setName($name);
        $client->setUser($user);

        $this->addFlash('info', 'sas.page.api_client.saved');

        $this->service->save($client);

        return new RedirectResponse($this->generateUrl(Profile::class));
    }
}
