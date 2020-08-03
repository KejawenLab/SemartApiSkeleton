<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Client;

use KejawenLab\ApiSkeleton\Entity\Client;
use KejawenLab\ApiSkeleton\Form\ClientType;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Client\ClientService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="CLIENT", actions={Permission::ADD})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Post extends AbstractController
{
    private ClientService $service;

    public function __construct(ClientService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/clients/add", methods={"GET", "POST"}, priority=1)
     */
    public function __invoke(Request $request)
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->service->save($client);

                $this->addFlash('info', 'sas.page.client.saved');

                return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_client_getall__invoke'));
            }
        }

        return $this->render('client/form.html.twig', [
            'page_title' => 'sas.page.client.add',
            'form' => $form->createView(),
        ]);
    }
}
