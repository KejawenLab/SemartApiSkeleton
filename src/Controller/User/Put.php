<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\User;

use Alpabit\ApiSkeleton\Entity\User;
use Alpabit\ApiSkeleton\Form\FormFactory;
use Alpabit\ApiSkeleton\Form\Type\UpdateUserType;
use Alpabit\ApiSkeleton\Security\Annotation\Permission;
use Alpabit\ApiSkeleton\Security\Model\UserInterface;
use Alpabit\ApiSkeleton\Security\Service\UserService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Noxlogic\RateLimitBundle\Annotation\RateLimit;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Permission(menu="USER", actions={Permission::EDIT})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Put extends AbstractFOSRestController
{
    private FormFactory $formFactory;

    private UserService $service;

    public function __construct(FormFactory $formFactory, UserService $service)
    {
        $this->formFactory = $formFactory;
        $this->service = $service;
    }

    /**
     * @Rest\Put("/users/{id}")
     *
     * @SWG\Tag(name="User")
     * @SWG\Parameter(
     *     name="user",
     *     in="body",
     *     type="object",
     *     description="User form",
     *     @Model(type=UpdateUserType::class)
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Update user",
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=User::class, groups={"read"})
     *     )
     * )
     *
     * @Security(name="Bearer")
     *
     * @RateLimit(limit=7, period=1)
     *
     * @param Request $request
     * @param string $id
     *
     * @return View
     */
    public function __invoke(Request $request, string $id): View
    {
        $user = $this->service->get($id);
        if (!$user instanceof UserInterface) {
            throw new NotFoundHttpException(sprintf('User with ID "%s" not found', $id));
        }

        $form = $this->formFactory->submitRequest(UpdateUserType::class, $request, $user);
        if (!$form->isValid()) {
            return $this->view((array) $form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        $this->service->save($user);

        return $this->view($this->service->get($user->getId()));
    }
}
