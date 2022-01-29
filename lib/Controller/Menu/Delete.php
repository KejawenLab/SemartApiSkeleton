<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Menu;

use OpenApi\Attributes\Tag;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Delete as Route;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\ApiSkeleton\Security\Service\MenuService;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'MENU', actions: [Permission::DELETE])]
final class Delete extends AbstractFOSRestController
{
    public function __construct(private readonly MenuService $service, private readonly TranslatorInterface $translator)
    {
    }
    #[Route(data: '/menus/{id}', name: Delete::class)]
    #[Security(name: 'Bearer')]
    #[Tag(name: 'Menu')]
    #[\OpenApi\Attributes\Response(response: 204, description: 'Delete menu')]
    public function __invoke(string $id): View
    {
        $menu = $this->service->get($id);
        if (!$menu instanceof MenuInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.menu.not_found', [], 'pages'));
        }

        $this->service->remove($menu);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
