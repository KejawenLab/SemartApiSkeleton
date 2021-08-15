<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Menu;

use DH\Auditor\Provider\Doctrine\Persistence\Reader\Reader;
use KejawenLab\ApiSkeleton\Audit\AuditService;
use KejawenLab\ApiSkeleton\Entity\Menu;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\ApiSkeleton\Security\Service\MenuService;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Permission(menu="MENU", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Get extends AbstractController
{
    public function __construct(private MenuService $service, private AuditService $audit, private Reader $reader)
    {
    }

    /**
     * @Route("/menus/{id}", name=Get::class, methods={"GET"})
     */
    public function __invoke(string $id): Response
    {
        $menu = $this->service->get($id);
        if (!$menu instanceof MenuInterface) {
            $this->addFlash('error', 'sas.page.menu.not_found');

            return new RedirectResponse($this->generateUrl(GetAll::class));
        }

        $audit = ['items' => []];
        if ($this->reader->getProvider()->isAuditable(Menu::class)) {
            $audit = $this->audit->getAudits($menu, $id, 3)->toArray();
        }

        $class = new ReflectionClass(Menu::class);

        return $this->render('menu/view.html.twig', [
            'page_title' => 'sas.page.menu.view',
            'context' => StringUtil::lowercase($class->getShortName()),
            'properties' => $class->getProperties(ReflectionProperty::IS_PRIVATE),
            'data' => $menu,
            'audits' => $audit['items'],
        ]);
    }
}
