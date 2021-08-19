<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Setting;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Setting\Model\SettingInterface;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Permission(menu="SETTING", actions={Permission::DELETE})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Delete extends AbstractFOSRestController
{
    public function __construct(private SettingService $service, private TranslatorInterface $translator)
    {
    }

    /**
     * @Rest\Delete("/settings/{id}", name=Delete::class)
     *
     * @OA\Tag(name="Setting")
     * @OA\Response(
     *     response=204,
     *     description="Delete setting"
     * )
     *
     * @Security(name="Bearer")
     */
    public function __invoke(string $id): View
    {
        $setting = $this->service->get($id);
        if (!$setting instanceof SettingInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.setting.not_found', [], 'pages'));
        }

        if (!$setting->isReserved()) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.setting.reserved_not_allowed', [], 'pages'));
        }

        $this->service->remove($setting);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
