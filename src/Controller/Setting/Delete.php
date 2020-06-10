<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Controller\Setting;

use Alpabit\ApiSkeleton\Security\Annotation\Permission;
use Alpabit\ApiSkeleton\Setting\Model\SettingInterface;
use Alpabit\ApiSkeleton\Setting\SettingService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Permission(menu="SETTING", actions={Permission::DELETE})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class Delete extends AbstractFOSRestController
{
    private SettingService $service;

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    /**
     * @Rest\Delete("/settings/{id}")
     *
     * @SWG\Tag(name="Setting")
     * @SWG\Response(
     *     response=204,
     *     description="Delete setting"
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
        $setting = $this->service->get($id);
        if (!$setting instanceof SettingInterface) {
            throw new NotFoundHttpException(sprintf('Setting with ID "%s" not found', $id));
        }

        $this->service->remove($setting);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
