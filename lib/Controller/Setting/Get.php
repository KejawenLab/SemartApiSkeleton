<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Setting;

use OpenApi\Attributes\Tag;
use OpenApi\Attributes\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get as Route;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\Setting;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Setting\Model\SettingInterface;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'SETTING', actions: [Permission::VIEW])]
final class Get extends AbstractFOSRestController
{
    public function __construct(private readonly SettingService $service, private readonly TranslatorInterface $translator)
    {
    }
    #[Route(data: '/settings/{id}', name: Get::class)]
    #[Security(name: 'Bearer')]
    #[Tag(name: 'Setting')]
    #[Response(response: 200, description: 'Setting detail', content: [new OA\MediaType(mediaType: 'application/json', new OA\Schema(type: 'object', ref: new Model(type: Setting::class, groups: ['read'])))])]
    public function __invoke(string $id): View
    {
        $setting = $this->service->get($id);
        if (!$setting instanceof SettingInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.setting.not_found', [], 'pages'));
        }

        return $this->view($setting);
    }
}
