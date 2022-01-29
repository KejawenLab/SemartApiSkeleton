<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Setting;

use OpenApi\Attributes\Tag;
use OpenApi\Attributes\RequestBody;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Put as Route;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Entity\Setting;
use KejawenLab\ApiSkeleton\Form\FormFactory;
use KejawenLab\ApiSkeleton\Form\SettingType;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use KejawenLab\ApiSkeleton\Setting\Model\SettingInterface;
use KejawenLab\ApiSkeleton\Setting\SettingService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'SETTING', actions: [Permission::EDIT])]
final class Put extends AbstractFOSRestController
{
    public function __construct(
        private readonly FormFactory $formFactory,
        private readonly SettingService $service,
        private readonly TranslatorInterface $translator,
    ) {
    }
    #[Route(data: '/settings/{id}', name: Put::class)]
    #[Security(name: 'Bearer')]
    #[Tag(name: 'Setting')]
    #[RequestBody(content: [new OA\MediaType(mediaType: 'application/json', new OA\Schema(type: 'object', ref: new Model(type: SettingType::class)))])]
    #[\OpenApi\Attributes\Response(response: 200, description: 'Setting updated', content: [new OA\MediaType(mediaType: 'application/json', new OA\Schema(type: 'object', ref: new Model(type: Setting::class, groups: ['read'])))])]
    public function __invoke(Request $request, string $id): View
    {
        $setting = $this->service->get($id);
        if (!$setting instanceof SettingInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.setting.not_found', [], 'pages'));
        }

        $form = $this->formFactory->submitRequest(SettingType::class, $request, $setting);
        if (!$form->isValid()) {
            return $this->view((array) $form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        $this->service->save($setting);

        return $this->view($this->service->get($setting->getId()));
    }
}
