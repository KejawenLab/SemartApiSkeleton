<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\ApiClient;

use KejawenLab\ApiSkeleton\ApiClient\ApiClientRequestService;
use KejawenLab\ApiSkeleton\Entity\ApiClientRequest;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="APICLIENT", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Report extends AbstractController
{
    public function __construct(private ApiClientRequestService $service, private Paginator $paginator)
    {
    }

    /**
     * @Route("/api-clients/{id}/logs", name=Report::class, methods={"GET"})
     */
    public function __invoke(Request $request): Response
    {
        $class = new ReflectionClass(ApiClientRequest::class);

        return $this->render('api_client/report.html.twig', [
            'page_title' => 'sas.page.api_client.report',
            'id' => $request->attributes->get('id'),
            'context' => StringUtil::lowercase($class->getShortName()),
            'properties' => $class->getProperties(ReflectionProperty::IS_PRIVATE),
            'paginator' => $this->paginator->paginate($this->service->getQueryBuilder(), $request, ApiClientRequest::class),
        ]);
    }
}
