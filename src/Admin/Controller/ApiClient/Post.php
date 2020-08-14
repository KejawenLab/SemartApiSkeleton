<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\ApiClient;

use KejawenLab\ApiSkeleton\ApiClient\ApiClientService;
use KejawenLab\ApiSkeleton\Entity\ApiClient;
use KejawenLab\ApiSkeleton\Form\ApiClientType;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="CLIENT", actions={Permission::ADD})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Post extends AbstractController
{
    private ApiClientService $service;

    public function __construct(ApiClientService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/api-clients/add", methods={"GET", "POST"}, priority=1)
     */
    public function __invoke(Request $request): Response
    {
        $client = new ApiClient();
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
