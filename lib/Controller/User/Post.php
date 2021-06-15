<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\User;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\User;
use KejawenLab\ApiSkeleton\Form\FormFactory;
use KejawenLab\ApiSkeleton\Form\UserType;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Security\Model\UserInterface;
use KejawenLab\ApiSkeleton\Security\Service\UserService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Permission(menu="USER", actions={Permission::ADD})
 *
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class Post extends AbstractFOSRestController
{
    public function __construct(private FormFactory $formFactory, private UserService $service)
    {
    }

    /**
     * @Rest\Post("/users", name=Post::class)
     *
     * @OA\Tag(name="User")
     * @OA\RequestBody(
     *     @OA\Schema(
     *         type="object",
     *         ref=@Model(type=UserType::class)
     *     ),
     *     description="User form"
     * )
     * @OA\Response(
     *     response=201,
     *     description= "User created",
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 ref=@Model(type=User::class, groups={"read"})
     *             )
     *         )
     *     }
     * )
     *
     * @Security(name="Bearer")
     */
    public function __invoke(Request $request): View
    {
        $form = $this->formFactory->submitRequest(UserType::class, $request);
        if (!$form->isValid()) {
            return $this->view((array) $form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        /** @var UserInterface $user */
        $user = $form->getData();
        $this->service->save($user);

        return $this->view($this->service->get($user->getId()), Response::HTTP_CREATED);
    }
}
