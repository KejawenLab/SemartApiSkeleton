<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Me;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\User;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Service\UserProviderFactory;
use KejawenLab\ApiSkeleton\Security\User as AuthUser;
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
final class Profile extends AbstractFOSRestController
{
    public function __construct(private readonly UserProviderFactory $userProviderFactory, private readonly TranslatorInterface $translator)
    {
    }

    #[Get(data: '/me', name: self::class, priority: 1)]
    #[Security(name: 'Bearer')]
    #[Response(
        response: 200,
        description: 'User profile',
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(ref: new Model(type: User::class, groups: ['read']), type: 'object'),
        ),
    )]
    public function __invoke(): View
    {
        $user = $this->getUser();
        if (!$user instanceof AuthUser) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.user.not_found', [], 'pages'));
        }

        return $this->view($this->userProviderFactory->getRealUser($user));
    }
}
