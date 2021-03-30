<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\ApiClient;

use KejawenLab\ApiSkeleton\ApiClient\ApiClientService;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Permission(menu="APICLIENT", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Download extends AbstractController
{
    private ApiClientService $service;

    private SerializerInterface $serializer;

    public function __construct(ApiClientService $service, SerializerInterface $serializer)
    {
        $this->service = $service;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api-clients/download", methods={"GET"})
     */
    public function __invoke(): Response
    {
        $records = $this->service->total();
        if (10000 < $records) {
            $this->addFlash('error', 'sas.page.error.too_many_records');

            return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_apiclient_getall__invoke'));
        }

        $response = new Response();
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', 'text/csv');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s_%s.csv"', 'api-clients', date('YmdHis')));

        $response->setContent($this->serializer->serialize($this->service->all(), 'csv', ['groups' => 'read']));

        return $response;
    }
}
