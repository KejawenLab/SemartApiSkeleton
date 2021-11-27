<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Controller\Cron;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Put as Route;
use FOS\RestBundle\View\View;
use KejawenLab\ApiSkeleton\Cron\CronService;
use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use KejawenLab\ApiSkeleton\Entity\Cron;
use KejawenLab\ApiSkeleton\Form\CronType;
use KejawenLab\ApiSkeleton\Form\FormFactory;
use KejawenLab\ApiSkeleton\Security\Annotation\Permission;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Permission(menu="CRON", actions={Permission::EDIT})
 *
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class Put extends AbstractFOSRestController
{
    public function __construct(
        private FormFactory $formFactory,
        private CronService $service,
        private TranslatorInterface $translator,
    ) {
    }

    /**
     *
     * @OA\Tag(name="Cron")
     * @OA\RequestBody(
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 ref=@Model(type=CronType::class)
     *             )
     *         )
     *     }
     * )
     * @OA\Response(
     *     response=200,
     *     description= "Cron updated",
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 ref=@Model(type=Cron::class, groups={"read"})
     *             )
     *         )
     *     }
     * )
     *
     * @Security(name="Bearer")
     */
    #[Route(data: '/cronjobs/{id}', name: Put::class, priority: -7)]
    public function __invoke(Request $request, string $id): View
    {
        $cron = $this->service->get($id);
        if (!$cron instanceof CronInterface) {
            throw new NotFoundHttpException($this->translator->trans('sas.page.cron.not_found', [], 'pages'));
        }

        $form = $this->formFactory->submitRequest(CronType::class, $request, $cron);
        if (!$form->isValid()) {
            return $this->view((array) $form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        $this->service->save($cron);

        return $this->view($this->service->get($cron->getId()));
    }
}
