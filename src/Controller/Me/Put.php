<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Me;

use Alpabit\ApiSkeleton\Entity\User;
use Alpabit\ApiSkeleton\Form\FormFactory;
use Alpabit\ApiSkeleton\Form\Type\UpdateProfileType;
use Alpabit\ApiSkeleton\Security\Annotation\Permission;
use Alpabit\ApiSkeleton\Security\Model\UserInterface;
use Alpabit\ApiSkeleton\Security\Service\UserService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Permission(menu="PROFILE", actions={Permission::EDIT})
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
     * @Rest\Put("/me")
     *
     * @SWG\Tag(name="Profile")
     * @SWG\Parameter(
     *     name="profile",
     *     in="body",
     *     type="object",
     *     description="Profile form",
     *     @Model(type=UpdateProfileType::class)
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Update profile",
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=User::class, groups={"read"})
     *     )
     * )
     * @Security(name="Bearer")
     *
     * @param Request $request
     *
     * @return View
     */
    public function __invoke(Request $request): View
    {
        /** @var UserInterface $user */
        $user = $this->getUser();
        $form = $this->formFactory->submitRequest(UpdateProfileType::class, $request, $user);
        if (!$form->isValid()) {
            return $this->view((array) $form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        if ($form['oldPassword']->getData() && $password = $form['newPassword']->getData()) {
            $user->setPlainPassword($password);
        }

        $this->service->save($user);

        return $this->view($this->service->get($user->getId()));
    }
}
