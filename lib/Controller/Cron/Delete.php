<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Cron;

use OpenApi\Attributes\Tag;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Delete as Route;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Cron\CronService;
use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
#[Permission(menu: 'CRON', actions: [Permission::DELETE])]
final class Delete extends AbstractFOSRestController
{
    public function __construct(private readonly CronService $service, private readonly TranslatorInterface $translator)
    {
    }
    #[Route(data: '/cronjobs/{id}', name: Delete::class, priority: -7)]
    #[Security(name: 'Bearer')]
    #[Tag(name: 'Cron')]
    #[\OpenApi\Attributes\Response(response: 204, description: 'Delete cron')]
    public function __invoke(string $id): View
    {
        $cron = $this->service->get($id);
        if (!$cron instanceof CronInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.cron.not_found', [], 'pages'));
        }

        $this->service->remove($cron);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
