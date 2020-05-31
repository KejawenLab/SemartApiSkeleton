<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\User;

use Alpabit\ApiSkeleton\Security\Annotation\Permission;
use Alpabit\ApiSkeleton\Security\Model\UserInterface;
use Alpabit\ApiSkeleton\Security\Service\UserService;
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
 * @Permission(menu="USER", actions={Permission::DELETE})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Delete extends AbstractFOSRestController
{
    private $service;

    private $logger;

    public function __construct(UserService $service, LoggerInterface $auditLogger)
    {
        $this->service = $service;
        $this->logger = $auditLogger;
    }

    /**
     * @Rest\Delete("/users/{id}")
     *
     * @SWG\Tag(name="User")
     * @SWG\Response(
     *     response=204,
     *     description="Delete user"
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
        $user = $this->service->get($id);
        if (!$user instanceof UserInterface) {
            throw new NotFoundHttpException(sprintf('User with ID "%s" not found', $id));
        }

        $this->logger->info(sprintf('[%s][%s][%s]', $this->getUser()->getUsername(), __CLASS__, $id));

        $this->service->remove($user);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
