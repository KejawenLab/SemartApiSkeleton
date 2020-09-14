<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Group;

use KejawenLab\ApiSkeleton\Entity\Permission as Entity;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation as Semart;
use KejawenLab\ApiSkeleton\Security\Service\PermissionService;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Semart\Permission(menu="GROUP", actions=Semart\Permission::VIEW)
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Permission extends AbstractController
{
    private PermissionService $service;

    private Paginator $paginator;

    public function __construct(PermissionService $service, Paginator $paginator)
    {
        $this->service = $service;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/groups/{id}/permissions", methods={"GET"})
     */
    public function __invoke(Request $request): Response
    {
        $class = new \ReflectionClass(Entity::class);

        return $this->render('group/permission.html.twig', [
            'page_title' => 'sas.page.permission.list',
            'context' => StringUtil::lowercase($class->getShortName()),
            'properties' => $class->getProperties(\ReflectionProperty::IS_PRIVATE),
            'paginator' => $this->paginator->paginate($this->service->getQueryBuilder(), $request, Entity::class),
        ]);
    }
}
