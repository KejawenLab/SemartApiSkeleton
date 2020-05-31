<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Controller\User;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\Semart\ApiSkeleton\Entity\User;
use KejawenLab\Semart\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\Semart\ApiSkeleton\Security\Service\UserService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Psr\Log\LoggerInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Permission(menu="USER", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Get extends AbstractFOSRestController
{
    private $service;

    private $logger;

    public function __construct(UserService $service, LoggerInterface $auditLogger)
    {
        $this->service = $service;
        $this->logger = $auditLogger;
    }

    /**
     * @Rest\Get("/users/{id}")
     *
     * @SWG\Tag(name="User")
     * @SWG\Response(
     *     response=200,
     *     description="Return user detail",
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=User::class, groups={"read"})
     *     )
     * )
     * @Security(name="Bearer")
     *
     * @param Request $request
     * @param string $id
     *
     * @return View
     */
    public function __invoke(Request $request, string $id): View
    {
        $this->logger->info(sprintf('[%s][%s][%s][%s]', $this->getUser()->getUsername(), __CLASS__, $id, serialize($request->query->all())));

        return $this->view($this->service->get($id));
    }
}
