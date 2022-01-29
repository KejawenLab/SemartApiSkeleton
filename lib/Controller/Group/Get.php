<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Group;

use OpenApi\Attributes\Tag;
use OpenApi\Attributes\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get as Route;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\Group;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Security\Service\GroupService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'GROUP', actions: [Permission::VIEW])]
final class Get extends AbstractFOSRestController
{
    public function __construct(private readonly GroupService $service, private readonly TranslatorInterface $translator)
    {
    }

    #[Route(data: '/groups/{id}', name: Get::class)]
    #[Security(name: 'Bearer')]
    #[Tag(name: 'Group')]
    #[Response(response: 200, description: 'Api client detail', content: [new OA\MediaType(mediaType: 'application/json', new OA\Schema(type: 'object', ref: new Model(type: Group::class, groups: ['read'])))])]
    public function __invoke(string $id): View
    {
        $group = $this->service->get($id);
        if (!$group instanceof GroupInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.group.not_found', [], 'pages'));
        }

        return $this->view($group);
    }
}
