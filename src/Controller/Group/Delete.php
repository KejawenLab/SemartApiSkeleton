<?php

declare(strict_types=1);

namespace App\Controller\Group;

use App\Security\Model\GroupInterface;
use App\Security\Service\GroupService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
class Delete extends AbstractFOSRestController
{
    private $service;

    public function __construct(GroupService $service)
    {
        $this->service = $service;
    }

    /**
     * @Rest\Delete("/groups/{id}")
     *
     * @SWG\Tag(name="Group")
     * @SWG\Response(
     *     response=204,
     *     description="Delete group"
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
        /** @var GroupInterface $group */
        $group = $this->service->get($id);
        if (!$group) {
            throw new NotFoundHttpException(sprintf('Group ID: "%s" not found', $id));
        }

        $this->service->remove($group);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
