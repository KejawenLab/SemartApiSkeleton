<?php

declare(strict_types=1);

namespace KejawenLab\Semart\ApiSkeleton\Controller\Setting;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\Semart\ApiSkeleton\Entity\Setting;
use KejawenLab\Semart\ApiSkeleton\Setting\SettingService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Psr\Log\LoggerInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Get extends AbstractFOSRestController
{
    private $service;

    private $logger;

    public function __construct(SettingService $service, LoggerInterface $auditLogger)
    {
        $this->service = $service;
        $this->logger = $auditLogger;
    }

    /**
     * @Rest\Get("/settings/{id}")
     *
     * @SWG\Tag(name="Setting")
     * @SWG\Response(
     *     response=200,
     *     description="Return setting detail",
     *     @SWG\Schema(
     *         type="object",
     *         ref=@Model(type=Setting::class, groups={"read"})
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
