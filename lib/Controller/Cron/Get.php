<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Cron;

use OpenApi\Attributes\Tag;
use OpenApi\Attributes\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get as Route;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Cron\CronService;
use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use KejawenLab\ApiSkeleton\Entity\Cron;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'CRON', actions: [Permission::VIEW])]
final class Get extends AbstractFOSRestController
{
    public function __construct(private readonly CronService $service, private readonly TranslatorInterface $translator)
    {
    }
    #[Route(data: '/cronjobs/{id}', name: Get::class, priority: -7)]
    #[Security(name: 'Bearer')]
    #[Tag(name: 'Cron')]
    #[Response(response: 200, description: 'Api client detail', content: [new OA\MediaType(mediaType: 'application/json', new OA\Schema(type: 'object', ref: new Model(type: Cron::class, groups: ['read'])))])]
    public function __invoke(string $id): View
    {
        $cron = $this->service->get($id);
        if (!$cron instanceof CronInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.cron.not_found', [], 'pages'));
        }

        return $this->view($cron);
    }
}
