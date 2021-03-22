<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\ApiClient;

use KejawenLab\ApiSkeleton\ApiClient\ApiClientService;
use KejawenLab\ApiSkeleton\Entity\ApiClient;
use KejawenLab\ApiSkeleton\Form\ApiClientType;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="APICLIENT", actions={Permission::ADD})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Post extends AbstractController
{
    private UserProviderFactory $userProviderFactory;

    private ApiClientService $service;

    public function __construct(UserProviderFactory $userProviderFactory, ApiClientService $service)
    {
        $this->userProviderFactory = $userProviderFactory;
        $this->service = $service;
    }

    /**
     * @Route("/api-clients/add", methods={"GET", "POST"}, priority=1)
     */
    public function __invoke(Request $request): Response
    {
        $user = $this->userProviderFactory->getRealUser($this->getUser());
        if (!$user instanceof UserInterface) {
            $this->addFlash('error', 'sas.page.user.not_found');

            return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_user_getall__invoke'));
        }

        $client = new ApiClient();
        $client->setUser($user);
        $form = $this->createForm(ApiClientType::class, $client);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->service->save($client);

                $this->addFlash('info', 'sas.page.api_client.saved');

                return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_apiclient_getall__invoke'));
            }
        }

        return $this->render('api_client/form.html.twig', [
            'page_title' => 'sas.page.api_client.add',
            'form' => $form->createView(),
        ]);
    }
}
