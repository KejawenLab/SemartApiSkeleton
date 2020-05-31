<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Group;

use Alpabit\ApiSkeleton\Security\Annotation\Permission;
use Alpabit\ApiSkeleton\Security\Model\GroupInterface;
use Alpabit\ApiSkeleton\Security\Service\GroupService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Security;
use Psr\Log\LoggerInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Permission(menu="GROUP", actions={Permission::DELETE})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Delete extends AbstractFOSRestController
{
    private $service;

    private $logger;

    public function __construct(GroupService $service, LoggerInterface $auditLogger)
    {
        $this->service = $service;
        $this->logger = $auditLogger;
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
        $group = $this->service->get($id);
        if (!$group instanceof GroupInterface) {
            throw new NotFoundHttpException(sprintf('Group with ID "%s" not found', $id));
        }

        $this->service->remove($group);

        $this->logger->info(sprintf('[%s][%s][%s]', $this->getUser()->getUsername(), __CLASS__, $id));

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
