<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Group;

use OpenApi\Attributes\Tag;
use OpenApi\Attributes\RequestBody;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Post as Route;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\Group;
use KejawenLab\ApiSkeleton\Form\FormFactory;
use KejawenLab\ApiSkeleton\Form\GroupType;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Security\Service\GroupService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'GROUP', actions: [Permission::ADD])]
final class Post extends AbstractFOSRestController
{
    public function __construct(private readonly FormFactory $formFactory, private readonly GroupService $service)
    {
    }

    #[Route(data: '/groups', name: Post::class)]
    #[Security(name: 'Bearer')]
    #[Tag(name: 'Group')]
    #[RequestBody(content: [new OA\MediaType(mediaType: 'application/json', new OA\Schema(type: 'object', ref: new Model(type: GroupType::class)))])]
    #[\OpenApi\Attributes\Response(response: 201, description: 'Group created', content: [new OA\MediaType(mediaType: 'application/json', new OA\Schema(type: 'object', ref: new Model(type: Group::class, groups: ['read'])))])]
    public function __invoke(Request $request): View
    {
        $form = $this->formFactory->submitRequest(GroupType::class, $request);
        if (!$form->isValid()) {
            return $this->view((array) $form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        /** @var GroupInterface $group */
        $group = $form->getData();
        $this->service->save($group);

        return $this->view($this->service->get($group->getId()), Response::HTTP_CREATED);
    }
}
