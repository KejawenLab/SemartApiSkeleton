<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Menu;

use OpenApi\Attributes\Tag;
use OpenApi\Attributes\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get as Route;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\Menu;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\MenuInterface;
use KejawenLab\ApiSkeleton\Security\Service\MenuService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'MENU', actions: [Permission::VIEW])]
final class Get extends AbstractFOSRestController
{
    public function __construct(private readonly MenuService $service, private readonly TranslatorInterface $translator)
    {
    }
    #[Route(data: '/menus/{id}', name: Get::class)]
    #[Security(name: 'Bearer')]
    #[Tag(name: 'Menu')]
    #[Response(response: 200, description: 'Menu detail', content: [new OA\MediaType(mediaType: 'application/json', new OA\Schema(type: 'object', ref: new Model(type: Menu::class, groups: ['read'])))])]
    public function __invoke(string $id): View
    {
        $menu = $this->service->get($id);
        if (!$menu instanceof MenuInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.menu.not_found', [], 'pages'));
        }

        return $this->view($menu);
    }
}
