<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Client;

use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Client\Model\ClientInterface;
use KejawenLab\ApiSkeleton\Client\ClientService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="CLIENT", actions={Permission::DELETE})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Delete extends AbstractController
{
    private ClientService $service;

    public function __construct(ClientService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/clients/{id}/delete", methods={"GET"})
     */
    public function __invoke(Request $request, string $id)
    {
        $client = $this->service->get($id);
        if (!$client instanceof ClientInterface) {
            $this->addFlash('error', 'sas.page.client.not_found');

            return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_client_getall__invoke'));
        }

        $this->service->remove($client);

        $this->addFlash('info', 'sas.page.client.deleted');

        return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_client_getall__invoke'));
    }
}
