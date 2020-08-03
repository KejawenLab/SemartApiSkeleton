<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Client;

use KejawenLab\ApiSkeleton\Entity\Client;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Client\ClientService;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="CLIENT", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class GetAll extends AbstractController
{
    private ClientService $service;

    private Paginator $paginator;

    public function __construct(ClientService $service, Paginator $paginator)
    {
        $this->service = $service;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/clients", methods={"GET"})
     */
    public function __invoke(Request $request)
    {
        $class = new \ReflectionClass(Client::class);

        return $this->render('client/all.html.twig', [
            'page_title' => 'sas.page.client.list',
            'context' => StringUtil::lowercase($class->getShortName()),
            'properties' => $class->getProperties(\ReflectionProperty::IS_PRIVATE),
            'paginator' => $this->paginator->paginate($this->service->getQueryBuilder(), $request, Client::class),
        ]);
    }
}
