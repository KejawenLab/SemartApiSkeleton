<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Me;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\Menu as Entity;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Service\PermissionService;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use KejawenLab\ApiSkeleton\Security\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'PROFILE', actions: [Permission::VIEW])]
#[Tag(name: 'Profile')]
final class Menu extends AbstractFOSRestController
{
    public function __construct(
        private readonly PermissionService $service,
        private readonly UserProviderFactory $userProviderFactory,
        private readonly TranslatorInterface $translator,
    ) {
    }

    #[Get(data: '/me/menus', name: Menu::class, priority: 1)]
    #[Security(name: 'Bearer')]
    #[Response(
        response: 200,
        description: 'Menu list for logged user',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'array',
                items: new OA\Items(ref: new Model(type: Entity::class, groups: ['read'])),
            ),
        ),
    )]
    public function __invoke(): View
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.user.not_found', [], 'pages'));
        }

        return $this->view($this->service->getByUser($this->userProviderFactory->getRealUser($user)));
    }
}
