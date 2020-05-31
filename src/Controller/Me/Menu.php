<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Controller\Me;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\Semart\ApiSkeleton\Entity\Menu as Entity;
use KejawenLab\Semart\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\Semart\ApiSkeleton\Security\Service\PermissionService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Psr\Log\LoggerInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Permission(menu="PROFILE", actions={Permission::VIEW})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Menu extends AbstractFOSRestController
{
    private $service;

    private $logger;

    public function __construct(PermissionService $service, LoggerInterface $auditLogger)
    {
        $this->service = $service;
        $this->logger = $auditLogger;
    }

    /**
     * @Rest\Get("/me/menus")
     *
     * @SWG\Tag(name="Profile")
     * @SWG\Response(
     *     response=200,
     *     description="Return menu list for logged user",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Entity::class, groups={"read"}))
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
        $this->logger->info(sprintf('[%s][%s][%s]', $this->getUser()->getUsername(), __CLASS__, serialize($request->query->all())));

        return $this->view($this->service->getByUser($this->getUser()));
    }
}
