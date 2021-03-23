<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\ApiClient;

use KejawenLab\ApiSkeleton\ApiClient\ApiClientService;
use KejawenLab\ApiSkeleton\ApiClient\Model\ApiClientInterface;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="APICLIENT", actions={Permission::DELETE})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Delete extends AbstractController
{
    private ApiClientService $service;

    public function __construct(ApiClientService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/api-clients/{id}/delete", methods={"GET"})
     */
    public function __invoke(string $id): Response
    {
        $client = $this->service->get($id);
        if (!$client instanceof ApiClientInterface) {
            $this->addFlash('error', 'sas.page.api_client.not_found');

            return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_apiclient_getall__invoke'));
        }

        $this->service->remove($client);

        $this->addFlash('info', 'sas.page.api_client.deleted');

        return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_apiclient_getall__invoke'));
    }
}
