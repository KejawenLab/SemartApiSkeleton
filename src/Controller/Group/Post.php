<?php

declare(strict_types=1);

namespace App\Controller\Group;

use App\Entity\Group;
use App\Form\FormFactory;
use App\Form\Type\GroupType;
use App\Security\Model\GroupInterface;
use App\Security\Service\GroupService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
class Post extends AbstractFOSRestController
{
    private $formFactory;

    private $service;

    public function __construct(FormFactory $formFactory, GroupService $service)
    {
        $this->formFactory = $formFactory;
        $this->service = $service;
    }

    /**
     * @Rest\Post("/groups")
     *
     * @SWG\Tag(name="Group")
     * @SWG\Parameter(
     *     name="group",
     *     in="body",
     *     type="object",
     *     description="Group form",
     *     @Model(type=GroupType::class)
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Crate new group",
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=Group::class, groups={"read"})
     *     )
     * )
     *
     * @Security(name="Bearer")
     *
     * @param Request $request
     *
     * @return View
     */
    public function __invoke(Request $request): View
    {
        $form = $this->formFactory->submitRequest(GroupType::class, $request);
        if (!$form->isValid()) {
            return $this->view($form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        /** @var GroupInterface $group */
        $group = $form->getData();
        $this->service->save($group);

        return $this->view($this->service->get($group->getId(), true), Response::HTTP_CREATED);
    }
}
