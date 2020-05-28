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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
class Put extends AbstractFOSRestController
{
    private $formFactory;

    private $service;

    public function __construct(FormFactory $formFactory, GroupService $service)
    {
        $this->formFactory = $formFactory;
        $this->service = $service;
    }

    /**
     * @Rest\Put("/groups")
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
     *     response=200,
     *     description="Update group",
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=Group::class, groups={"read"})
     *     )
     * )
     *
     * @Security(name="Bearer")
     *
     * @param Request $request
     * @param string $id
     *
     * @return View
     */
    public function __invoke(Request $request, string $id): View
    {
        $group = $this->service->get($id);
        if (!$group) {
            throw new NotFoundHttpException(sprintf('Group ID: "%s" not found', $id));
        }

        $form = $this->formFactory->submitRequest(GroupType::class, $request, $group);
        if (!$form->isValid()) {
            return $this->view($form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        /** @var GroupInterface $group */
        $group = $form->getData();
        $this->service->save($group);

        return $this->view($this->service->get($group->getId(), true));
    }
}
