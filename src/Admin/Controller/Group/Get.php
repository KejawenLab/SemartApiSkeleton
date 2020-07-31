<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Admin\Controller\Group;

use KejawenLab\ApiSkeleton\Entity\Group;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Security\Service\GroupService;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Mapping\PropertyMappingFactory;

/**
 * @Permission(menu="GROUP", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Get extends AbstractController
{
    private GroupService $service;

    private PropertyMappingFactory $mapping;

    public function __construct(GroupService $service, PropertyMappingFactory $mapping)
    {
        $this->service = $service;
        $this->mapping = $mapping;
    }

    /**
     * @Route("/groups/{id}", methods={"GET"})
     */
    public function __invoke(Request $request, string $id)
    {
        $group = $this->service->get($id);
        if (!$group instanceof GroupInterface) {
            $this->addFlash('error', 'sas.page.group.not_found');

            return new RedirectResponse($this->generateUrl('kejawenlab_apiskeleton_admin_group_getall__invoke'));
        }

        $class = new \ReflectionClass(Group::class);

        return $this->render('group/view.html.twig', [
            'page_title' => 'sas.page.group.view',
            'context' => StringUtil::lowercase($class->getShortName()),
            'properties' => $class->getProperties(\ReflectionProperty::IS_PRIVATE),
            'data' => $group,
        ]);
    }
}
