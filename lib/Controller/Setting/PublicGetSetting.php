<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Setting;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\Setting;
use KejawenLab\ApiSkeleton\Setting\Model\SettingInterface;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class PublicGetSetting extends AbstractFOSRestController
{
    public function __construct(private readonly SettingService $service, private readonly TranslatorInterface $translator)
    {
    }

    #[Get(data: '/settings/public/{id}', name: PublicGetSetting::class, priority: 1)]
    #[Tag(name: 'Setting')]
    #[Response(response: 200, description: 'Setting list', content: [new OA\MediaType(mediaType: 'application/json', new OA\Schema(type: 'array', new OA\Items(ref: new Model(type: Setting::class, groups: ['read']))))])]
    public function __invoke(string $id): View
    {
        $setting = $this->service->getPublicSetting($id);
        if (!$setting instanceof SettingInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.setting.not_found', [], 'pages'));
        }

        return $this->view($setting);
    }
}
