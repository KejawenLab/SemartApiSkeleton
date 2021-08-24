<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Group;

use KejawenLab\ApiSkeleton\Entity\Permission as Entity;
use KejawenLab\ApiSkeleton\Pagination\Paginator;
use KejawenLab\ApiSkeleton\Security\Annotation as Semart;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Security\Service\GroupService;
use KejawenLab\ApiSkeleton\Security\Service\PermissionService;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Semart\Permission(menu="GROUP", actions=Semart\Permission::VIEW)
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Permission extends AbstractController
{
    public function __construct(
        private PermissionService $service,
        private GroupService $groupService,
        private Paginator $paginator,
        private SettingService $settingService,
    ) {
    }

    /**
     * @Route(path="/groups/{id}/permissions", name=Permission::class, methods={"GET"})
     */
    public function __invoke(Request $request, string $id): Response
    {
        $group = $this->groupService->get($id);
        if (!$group instanceof GroupInterface) {
            $this->addFlash('error', 'sas.page.group.not_found');

            return new RedirectResponse($this->generateUrl(Main::class));
        }

        $request->query->set($this->settingService->getPerPageField(), 10);

        $class = new ReflectionClass(Entity::class);

        return $this->render('group/permission.html.twig', [
            'page_title' => 'sas.page.permission.list',
            'group' => $group,
            'context' => StringUtil::lowercase($class->getShortName()),
            'properties' => $class->getProperties(ReflectionProperty::IS_PRIVATE),
            'paginator' => $this->paginator->paginate($this->service->getQueryBuilder(), $request, Entity::class),
        ]);
    }
}
