<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Me;

use KejawenLab\ApiSkeleton\Admin\AdminContext;
use KejawenLab\ApiSkeleton\ApiClient\ApiClientService;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use KejawenLab\ApiSkeleton\Security\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class DeleteApiClient extends AbstractController
{
    public function __construct(private UserProviderFactory $userProviderFactory, private ApiClientService $service)
    {
    }

    /**
     * @Route(path="/me/api-clients/{id}/delete", name=DeleteApiClient::class, methods={"GET"})
     */
    public function __invoke(Request $request, string $id): Response
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
        $client = $this->service->getByIdAndUser($id, $user);
        if (null === $client) {
            $this->addFlash('error', 'sas.page.api_client.not_found');

            return new RedirectResponse($this->generateUrl(Profile::class));
        }

        $this->service->remove($client);

        $this->addFlash('info', 'sas.page.api_client.deleted');

        return new RedirectResponse($this->generateUrl(Profile::class));
    }
}
