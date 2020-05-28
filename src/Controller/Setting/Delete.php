<?php

declare(strict_types=1);

namespace App\Controller\Setting;

use App\Setting\Model\SettingInterface;
use App\Setting\SettingService;
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
        /** @var SettingInterface $setting */
        $setting = $this->service->get($id);
        if (!$setting) {
            throw new NotFoundHttpException(sprintf('Setting ID: "%s" not found', $id));
        }

        $this->service->remove($setting);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
